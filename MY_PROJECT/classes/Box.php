<?php
require_once "../connection/Database.php";
class Box
{
    private $conn;
    private $table = "boxes";
    public $name ;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (box_name) VALUES (:name)");
        return $stmt->execute([":name" => $name]);
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM boxes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {  
        $stmt = $this->conn->prepare("
            DELETE students FROM students
            JOIN classes ON students.class_id = classes.id
            WHERE classes.box_id = :id
                                    ");
        $stmt->execute([":id" => $id]);

       
        $stmt = $this->conn->prepare("
            DELETE FROM classes WHERE box_id = :id
        ");
        $stmt->execute([":id" => $id]);


        $stmt = $this->conn->prepare("
            DELETE FROM boxes WHERE id = :id
        ");
        return $stmt->execute([":id" => $id]);
    }

}