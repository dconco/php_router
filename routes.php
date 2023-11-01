<?php

require_once './autoload.php';

use PhpSlides\Router\Route;
use PhpSlides\Router\view;

$api_dir = 'Controller/UserController.php';

// Register API's
// Route::any("/api/v1/account/login", $api_dir);
// Route::any("/api/v1/account/register", $api_dir);
// Route::any("/api/v1/account/logout", $api_dir);
// Route::any("/api/v1/profile/{user_id}", $api_dir);
// Route::any("/api/v1/dashboard", $api_dir);

// // Register views page
// Route::any('/login', 'views/login.php');
// Route::any('/signup', 'views/signup.php');
// Route::any('/profile/{user_id}', 'views/profile.php');


/* REGISTER ROUTES */

// view route
Route::view('/|/home', 'views::index');

// get route
Route::get('/profile/{user_id}/post/{post_id}', function ($user_id, $post_id)
{
    echo ($_GET['data']);
    return $user_id . '<br>' . $post_id;
});

// Handle not found errors
Route::notFound(function ()
{
    header('Content-Type: text/html, charset=utf-8');
    return file_get_contents(view::render('views::errors::404'));
});