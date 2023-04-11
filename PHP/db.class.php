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

    public function getTasks() {
        $sql = 'SELECT *
        FROM tasks
        ORDER BY date_created ASC';

        $sth = $this->pdo->prepare($sql);

        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_OBJ);
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

    public function updateTask($id, $status) {
        $sql = 'UPDATE tasks
        SET status = :status
        WHERE id=:id';

        $sth = $this->pdo->prepare($sql);
        $r = $sth->execute([':id' => $id, ':status' => $status]);

        if(!$r) return array("message" => "Erreur de modification en base de données");
    }

    public function deleteTask($id) {
        $sql = 'DELETE
        FROM tasks
        WHERE id=:id';

        $sth = $this->pdo->prepare($sql);
        $r = $sth->execute([':id' => $id]);

        if($r) return array("message" => "La tâche est supprimée !");
        else return array("message" => "Erreur de suppression en base de données");
    }

    public function commonTasks(){
        $sql = 'SELECT COUNT(`task`) AS nombre, task FROM `tasks` GROUP BY `task` ORDER BY nombre DESC';

        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        $allResults = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $allResults;
    }
}
