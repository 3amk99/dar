<?php
require_once "../connection/Database.php";
class User
{
    private $conn;
    private $table = "users";
    
    public $email ;
    public $password ;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($email, $password)
    {
    
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(["email" => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify( $password, $user['password'] ))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            return true;
        }

        return false;
    }
}