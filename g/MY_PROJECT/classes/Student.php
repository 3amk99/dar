<?php
require_once "../connection/Database.php";
class Student {
    private $conn;
    private $table = "students";

    public $name ;
    public $photo ;
    public $class_id ;
    

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($name, $photo, $class_id)
    {
        $stmt = $this->conn->prepare
        (
            "INSERT INTO {$this->table}
            (name, photo, class_id)
            VALUES (:name, :photo, :class_id)"
        );

        return $stmt->execute
        ([
            ":name" => $name,
             ":photo" => $photo,
            ":class_id" => $class_id
        ]);
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare
        ("
            SELECT students.*, classes.name AS class_name
            FROM students
            JOIN classes ON students.class_id = classes.id
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
         }

public function getByClass($class_id)
{
    $query = "
                SELECT
                    students.*,
                    classes.class_name AS class_name,
                    boxes.box_name AS box_name

                FROM students

                JOIN classes
                ON students.class_id = classes.id

                JOIN boxes
                ON classes.box_id = boxes.id

                WHERE students.class_id = :class_id
            ";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
        ":class_id" => $class_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function delete($id)
{
    $stmt = $this->conn->prepare("
        DELETE FROM students WHERE id = :id
    ");

    return $stmt->execute([":id" => $id]);
}
}