<?php

class Db {
    private $pdo;

    public function __construct() {
        if ($this->pdo === null) {
            $pdo = new PDO('mysql:dbname=todolist;host=localhost', 'todolist_user', 'todolist_pass');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    public function getTasks() {
        $sql = 'SELECT *
        FROM tasks
        ORDER BY date ASC';

        $sth = $this->pdo->prepare($sql);

        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
}
