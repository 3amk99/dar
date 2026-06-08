<?php
require_once "../connection/Database.php";
class SchoolClass
{
    private $conn;
    private $table = "classes";

    public $name;
    public $box_id ;
    public $id ;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name, $box_id)
    {
        $stmt = $this->conn->prepare
        (
            "INSERT INTO {$this->table} (class_name, box_id) VALUES (:class_name, :box_id)"
        );

        return $stmt->execute
        ([
            ":class_name" => $name,
            ":box_id" => $box_id
        ]);
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM classes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function get_By_id_Box($box_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE box_id = :box_id");
        $stmt->execute(["box_id" => $box_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("
            DELETE FROM students WHERE class_id = :id
        ");
        $stmt->execute([":id" => $id]);



        $stmt = $this->conn->prepare("DELETE FROM classes WHERE id = :id
                                    ");

        return $stmt->execute([":id" => $id]);
    }
}