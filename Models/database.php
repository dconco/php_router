<?php
/**
 * DATABASE CONNECTION ENDPOINT
 */
class DB
{
    /**
     * CONNECT TO DATABASE
     **/

    public $conn;
    public $response = [];
    public function __construct()
    {
        try {
            session_start();
            $this->conn = new mysqli("localhost", "root", "", "api");
        } catch (err) {
            http_response_code(1001);
            exit("Connection Refused!");
        }
    }

    /* GET REQUEST */
    function GET($table, $data = "*", $option = "")
    {
        if (empty($table)) {
            $this->response = [
                "status" => 400,
                "statusText" => "Bad Request",
                "message" => "First GET Parameter is required!",
            ];
            return $this->response;
            exit();
        }

        if (empty($data)) {
            $sql = "SELECT * FROM {$table} {$option}";
        } else {
            $sql = "SELECT {$data} FROM {$table} {$option}";
        }

        if ($this->conn->query($sql)) {
            $this->response = [
                "status" => 200,
                "statusText" => "OK",
                "query" => $this->conn->query($sql),
                "message" => "Successfully Get Requested Users.",
            ];
        } else {
            $this->response = [
                "status" => 500,
                "statusText" => "Server Internal Error",
                "message" => "Error Occured! Get Request Terminated!",
            ];
        }

        return $this->response;
    }

    /* POST REQUEST */
    function POST($table, $data = [])
    {
        if (array_key_exists("email", $data) && $table == "users") {
            $get_email = $this->GET(
                $table,
                "email",
                "WHERE email = '{$data["email"]}'"
            );

            if ($get_email["query"]->num_rows > 0) {
                $this->response = [
                    "status" => 400,
                    "statusText" => "Bad Request",
                    "message" => "The Email has already been used.",
                ];
                return $this->response;
                exit();
            }
        }

        $data_key = "";
        $data_value = "";
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data_key .= $key . ", ";
                $data_value .= "'{$value}'" . ", ";
            } else {
                $data_key .= $key . ", ";
                $data_value .= $value . ", ";
            }
        }

        $data_key = substr($data_key, 0, strlen($data_key) - 2);
        $data_value = substr($data_value, 0, strlen($data_value) - 2);

        $sql = "INSERT INTO {$table} (
            {$data_key}
        ) VALUES (
            {$data_value}
        )";

        if ($this->conn->query($sql)) {
            $this->response = [
                "status" => 200,
                "statusText" => "OK",
                "data" => $data,
                "message" => "User Registered Successfully.",
            ];
        } else {
            $this->response = [
                "status" => 500,
                "statusText" => "Server Internal Error",
                "message" => "Error Occured! Registration Terminated!",
            ];
        }

        return $this->response;
    }

    function UPDATE($table, $data = [], $option = [])
    {
    }

    function DELETE($table, $option = [])
    {
    }

    /* EXTRA DB ENDPOINT */
    function GET_USERS($option = "WHERE id=1")
    {
        return $this->GET("users", "", $option);
    }
}
