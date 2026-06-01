<?php

class Database
{
    private $host = "localhost" ;
    private $db_name = "my_project" ;
    private $username = "root" ;
    private $password = "baba123" ;
    public  $conn ;

    public function connect()
    {
        $this->conn = null;

        try
        {
            $this->conn = new PDO
            (
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
        }
        catch (PDOException $e)
        {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
