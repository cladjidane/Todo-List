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
        /**
         * TODO
         * 
         * Exemple de requête SQL
         * UPDATE tasks SET status = :status WHERE id=:id
         * 
         * Voir prepare et execute dans addTask pour vous inspirer 
         */
    }

    public function deleteTask($id) {
        /**
         * TODO
         * 
         * A vous de jouer :)
         */
    }
}
