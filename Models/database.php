<?php
/**
 * DATABASE ENDPOINT
 */
class DB
{

    public $conn;
    public $response = [];
    public $get_last_insert;

    /**
     * CONNECTION TO DATABASE
     **/
    public function __construct()
    {
        try
        {
            $db_host = getenv('DB_HOST');
            $db_user = getenv('DB_USER');
            $db_pass = getenv('DB_PASS');
            $db_base = getenv('DB_BASE');

            $this->conn = new mysqli($db_host, $db_user, $db_pass, $db_base);

            if ($this->conn->connect_error)
            {
                http_response_code(1001);
                exit("Connection Refused!");
            }
        }
        catch (Exception $e)
        {
            http_response_code(1001);
            exit("Connection Refused! " . $e->getMessage());
        }
    }


    /**
     * GET REQUEST FROM DATABASE
     **/
    function GET(string $table, string $data, $where = '', $join = '', $limit = '', $order_by = '')
    {
        if (empty($table))
        {
            $this->response = [
                "status" => 400,
                "statusText" => "Bad Request",
                "message" => "First GET Parameter is required!",
            ];

            return $this->response;
        }

        $sql = "SELECT {$data} FROM {$table}";

        !empty($where) && $sql .= " WHERE $where";
        !empty($join) && $sql .= " JOIN $join";
        !empty($limit) && $sql .= " LIMIT $limit";
        !empty($order_by) && $sql .= " ORDER BY $order_by";

        try
        {
            $query = $this->conn->query($sql);
            if ($query == true)
            {
                $this->response = [
                    "status" => 200,
                    "statusText" => "OK",
                    "query" => $query,
                    "message" => "Successfully Get Requested Users."
                ];
            }
        }
        catch (Exception $e)
        {
            $this->response = [
                "status" => 500,
                "statusText" => "Internal Server Error",
                "message" => $e->getMessage()
            ];
        }
        finally
        {
            return $this->response;
        }
    }

    /**
     * REGISTER USER REQUEST TO DATABASE
     * Create new user and POST it to database
     * Can only be used with REGISTER_USER db method
     **/
    function REGISTER_USER(array $data)
    {
        if (array_key_exists("email", $data) || array_key_exists("user_id", $data))
        {
            $get_email = $this->GET("users", "email", "email = '{$data["email"]}' OR user_id = '{$data["user_id"]}'");

            if ($get_email["query"]->num_rows > 0)
            {
                $this->response = [
                    "status" => 403,
                    "statusText" => "Forbidden",
                    "message" => "The Email or User ID has already been used.",
                ];

                return $this->response;
            }
        }

        $values     = [];
        $data_key   = '';
        $data_value = '';
        $data_types = '';

        // Loop through the given user info in the array
        foreach ($data as $key => $value)
        {
            $data_key .= $key . ', ';
            $data_value .= '?, ';
            $data_types .= 's';
            array_push($values, $value);
        }

        $data_key   = substr($data_key, 0, strlen($data_key) - 2);
        $data_value = substr($data_value, 0, strlen($data_value) - 2);
        $data_types = substr($data_types, 0, strlen($data_types) - 1);

        // Prepare and Bind
        $stmt = $this->conn->prepare("INSERT INTO users ($data_key) VALUES ($data_value)");
        $stmt->bind_param("i$data_types", ...$values);

        try
        {
            $exec = $stmt->execute();

            if ($exec == true)
            {
                $this->get_last_insert = $this->GET("users", "id, user_id, fullname, email, reg_date", "id = {$this->conn->insert_id}");

                $access_token = [ 'access_token' => getenv('JWT_SECRET_TOKEN') ];
                $data2        = array_merge($this->get_last_insert['query']->fetch_assoc(), $access_token);

                $_SESSION['user_id'] = $data['user_id'];
                setcookie('user_id', $data['user_id'], time() + 86400, '/');
                setcookie('access_token', getenv('JWT_SECRET_TOKEN'), time() + 86400, '/');


                // SEND RESPONSE
                $this->response = [
                    "status" => 200,
                    "statusText" => "OK",
                    "data" => (object) $data2,
                    "message" => "User Registered Successfully.",
                ];

                $stmt->close();
            }
        }
        catch (Exception $e)
        {
            $this->response = [
                "status" => 500,
                "statusText" => "Server Internal Error",
                "message" => $e->getMessage(),
            ];
        }
        finally
        {
            return $this->response;
        }
    }


    /**
     * CREATE NEW POST REQUEST TO DATABASE
     * Insert new data to database info
     **/
    function POST(string $table, array $data)
    {
        if ($table === "users")
        {
            $this->response = [
                "status" => 400,
                "statusText" => "Bad Request",
                "message" => "Can't POST request to users table, use REGISTER USER instead!",
            ];
            return $this->response;
        }

        $data_key   = "";
        $data_value = "";
        foreach ($data as $key => $value)
        {
            if (is_string($value))
            {
                $data_key .= $key . ", ";
                $data_value .= "'{$value}'" . ", ";
            }
            else
            {
                $data_key .= $key . ", ";
                $data_value .= $value . ", ";
            }
        }

        $data_key   = substr($data_key, 0, strlen($data_key) - 2);
        $data_value = substr($data_value, 0, strlen($data_value) - 2);

        $sql = "INSERT INTO {$table} (
            {$data_key}
        ) VALUES (
            {$data_value}
        )";


        try
        {
            $query = $this->conn->query($sql);

            if ($query == true)
            {
                $this->get_last_insert = $this->GET($table, "*", "id = {$this->conn->insert_id}");

                // SEND RESPONSE
                $this->response = [
                    "status" => 200,
                    "statusText" => "OK",
                    "data" => $this->get_last_insert['query']->fetch_assoc(),
                    "message" => "Information Successfully added to database.",
                ];
            }
        }
        catch (Exception $e)
        {
            $this->response = [
                "status" => 500,
                "statusText" => "Internal Server Error",
                "message" => $e->getMessage(),
            ];
        }
        finally
        {
            return $this->response;
        }
    }

    function UPDATE(string $table, array $data, array $option)
    {
    }

    function DELETE(string $table, array $option)
    {
    }

    /**
     * GET ALL USERS FROM DATABASE
     **/
    function GET_USERS(string $option = "id=1")
    {
        return $this->GET("users", "*", $option);
    }
}