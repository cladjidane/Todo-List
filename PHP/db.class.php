<?php

class Db {
    private $pdo;

    public function __construct() {
        if ($this->pdo === null) {
            $pdo = new PDO('mysql:dbname=todolist;host=localhost', 'todolist_user', 'todolist_pass');
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    public function getTask($id) {
        $sql = 'SELECT *
        FROM tasks
        WHERE id=:id';

        $sth = $this->pdo->prepare($sql);
        $sth->execute([':id' => $id]);

        return $sth->fetch(PDO::FETCH_OBJ);
    }

    public function addtask($task) {
        $sql = "INSERT INTO `tasks`(`task`, `date_created`, `status`) VALUES (:task,CURRENT_TIMESTAMP(),'pending')";
        $sth = $this->pdo->prepare($sql);
        $r = $sth->execute(
            array(
                ":task"=>$task
            )
        );
        $id = $this->pdo->lastInsertId();
        if($r) return array($this->getTask($id));
        else return array("message" => "Erreur d'insertion en base de données");
    }
}
