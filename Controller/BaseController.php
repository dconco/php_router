<?php

namespace Dconco\Router;

use Exception;

/**
 * CREATE A NEW ROUTE
 * Create route & api that accept different methods
 * 
 * @author Dave Conco <concodave@gmail.com>
 * @link https://github.com/dconco/php_router
 * @category api, router
 * @package php_router
 * @version ${1:1.0.0}
 * @return void
 */

class Route
{
    private static function simpleRoute(string $route, $callback)
    {
        //replacing first and last forward slashes
        //$_REQUEST['uri'] will be empty if req uri is /

        if (!empty($_REQUEST["uri"]))
        {
            $route = preg_replace("/(^\/)|(\/)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);

            $uri = preg_split("/[|]/", $route);
        }
        else
        {
            $reqUri = "";
            $route = preg_replace("/(^\/)|(\/)/", "", $route);
            $uri = preg_split("/[|]/", $route);
        }

        if (in_array($reqUri, $uri))
        {
            print_r($callback());
            exit;
        }
    }


    /**
     * ANY REQUEST FROM ROUTE
     * 
     * Accept GET, POST, OPTION, PUT, DELETE, UPDATE or any other method
     */
    public static function any(string $route, $callback)
    {
        //will store all the parameters value in this array
        $req = [];
        $req_value = [];

        //will store all the parameters names in this array
        $paramKey = [];

        //finding if there is any {?} parameter in $route
        preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);

        //if the route does not contain any param call simpleRoute();
        if (empty($paramMatches[0]))
        {
            self::simpleRoute($route, $callback);
            return;
        }

        //setting parameters names
        foreach ($paramMatches[0] as $key)
        {
            $paramKey[] = $key;
        }

        //replacing first and last forward slashes
        //$_REQUEST['uri'] will be empty if req uri is /

        if (!empty($_REQUEST["uri"]))
        {
            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);
        }
        else
        {
            $reqUri = "/";
        }

        //exploding route address
        $uri = explode("/", $route);

        //will store index number where {?} parameter is required in the $route
        $indexNum = [];

        //storing index number, where {?} parameter is required with the help of regex
        foreach ($uri as $index => $param)
        {
            if (preg_match("/{.*}/", $param))
            {
                $indexNum[] = $index;
            }
        }

        //exploding request uri string to array to get
        //the exact index number value of parameter from $_REQUEST['uri']
        $reqUri = explode("/", $reqUri);

        //running for each loop to set the exact index number with reg expression
        //this will help in matching route
        foreach ($indexNum as $key => $index)
        {
            //in case if req uri with param index is empty then return
            //because url is not valid for this route
            if (empty($reqUri[$index]))
            {
                return;
            }

            //setting params with params names
            $req[$paramKey[$key]] = $reqUri[$index];
            $req_value[] = $reqUri[$index];

            //this is to create a regex for comparing route address
            $reqUri[$index] = "{.*}";
        }

        //converting array to string
        $reqUri = implode("/", $reqUri);

        //replace all / with \/ for reg expression
        //regex to match route is ready !
        $reqUri = str_replace("/", "\\/", $reqUri);

        //now matching route with regex
        if (preg_match("/$reqUri/", $route))
        {
            print_r($callback(...$req_value));
            exit;
        }
    }


    /**
     * VIEW ROUTE METHOD 
     * 
     * This method allow users to view files directly from the route url
     */
    public static function view(string $route, string $view)
    {
        //replacing first and last forward slashes
        //$_REQUEST['uri'] will be empty if req uri is /

        if (!empty($_REQUEST["uri"]))
        {
            $route = preg_replace("/(^\/)|(\/)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);

            $uri = preg_split("/[|]/", $route);
        }
        else
        {
            $reqUri = "";
            $route = preg_replace("/(^\/)|(\/)/", "", $route);
            $uri = preg_split("/[|]/", $route);
        }

        if (in_array($reqUri, $uri))
        {
            $view = view::render($view);

            header('Content-type: text/html, charset=utf-8');
            print_r(file_get_contents($view));
            exit;
        }
    }


    /**
     * REDIRECT ROUTE METHOD
     * 
     * This method redirects the routes url to the giving url directly
     */
    public static function redirect(string $route, string $new_url, int $code = 301)
    {
        if (!empty($_REQUEST["uri"]))
        {
            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST["uri"]);
        }
        else
        {
            $reqUri = "/";
        }

        if ($reqUri === $route)
        {
            header("Location: " . $new_url, true, $code);
            exec('');
        }
    }


    /**
     * GET ROUTE METHOD
     */
    public static function get(string $route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        self::any($route, $callback);
    }


    /**
     * POST ROUTE METHOD
     */
    public static function post(string $route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        self::any($route, $callback);
    }


    /**
     * Not Found Error
     */
    public static function notFound($callback)
    {
        http_response_code(404);
        print_r($callback());
    }
}


class view
{
    public static function render($view)
    {
        try
        {
            if (is_file($view))
            {
                print_r(file_get_contents($view));
                exit;
            }
            else
            {
                $file = preg_split('/(::)|::/', $view);
                $view = '';

                foreach ($file as $index => $item)
                {
                    if ($index !== count($file) - 1)
                    {
                        $view .= '/' . $item;
                    }
                    else
                    {
                        $view .= '/';
                    }
                }

                $ext = [ '.view.php', '.php', '.html', '.htm', '.txt' ];

                for ($i = 0; $i < count($ext); $i++)
                {
                    $file_uri = 'public' . $view . $file[count($file) - 1] . $ext[$i];

                    if (is_file($file_uri))
                    {
                        return $file_uri;
                    }
                }
                exit;
            }
        }
        catch ( Exception $e )
        {
            print($e->getMessage());
        }
    }
}