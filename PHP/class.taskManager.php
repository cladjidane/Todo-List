<?php

class TaskManager {
  private $db;
  private $notice;

  public function __construct() {
    $this->db = new PDO('sqlite:tasks.db');
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "CREATE TABLE IF NOT EXISTS tasks (
                    id INTEGER PRIMARY KEY,
                    task TEXT NOT NULL,
                    date TEXT,
                    priority INTEGER,
                    status TEXT
                  )";
    $this->db->exec($query);

    $this->controllerTask();
  }

  public function getTasks($status = "wip") {
    $result = $this->db->query("SELECT * FROM tasks WHERE status='$status' ");
    $tasks = $result->fetchAll(PDO::FETCH_ASSOC);

    usort($tasks, function ($a, $b) {
      return $a['priority'] - $b['priority'];
    });

    return $tasks;
  }

  public function checkTaskDeadline($task) {
    $currentDate = new DateTime();
    $taskDate = DateTime::createFromFormat('Y-m-d', $task['date']);
    $dateFormatted = $taskDate->format('d-m-Y');

    if ($taskDate < $currentDate) {
      $daysPassed = $currentDate->diff($taskDate)->format('%a');
      $daysLimits = ['1' => 1, '2' => 3, '3' => 7];

      if ($daysPassed > $daysLimits[$task['priority']]) {
        return "<span><span class=\"late\"></span>$dateFormatted ($daysPassed jour" . ($daysPassed > 1 ? 's' : '') . ")</span>";
      }
    }

    return "<span><span class=\"on-time\"></span>$dateFormatted</span>";
  }

  public function controllerTask() {
    $request_mode = $_REQUEST['mode'] ?? null;

    if ($request_mode) {
      switch ($request_mode) {
        case 'add':
          $this->addTask($_REQUEST);
          break;
        case 'update':
          $this->updateTask($_REQUEST['id'], $_REQUEST['status']);
          break;
        case 'delete':
          $this->deleteTask($_REQUEST['id']);
          break;
      }
    }
  }

  private function addTask($data) {
    if ($this->checkData()) {
      $taskName = !empty($data['field-task']) ? $data['field-task'] : ($data['select-task'] ?? '');
      $taskPriority = $data['priority'];
      $taskDate = $data['date'];

      $stmt = $this->db->prepare("INSERT INTO tasks (task, date, priority, status) VALUES (?,?,?,?)");
      $success = $stmt->execute([$taskName, $taskDate, $taskPriority, 'wip']);

      $this->notice = $success ? 'La tâche a bien été ajoutée' : 'Erreur de traitement';
    } else {
      $this->notice = 'Erreur';
    }
  }

  private function updateTask($taskId, $currentStatus) {
    $status = $currentStatus === 'wip' ? 'finish' : 'wip';

    $stmt = $this->db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $success = $stmt->execute([$status, $taskId]);

    $this->notice = $success ? 'La tâche a bien été mise à jour' : 'Erreur de traitement';
  }

  private function deleteTask($taskId) {
    $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
    $success = $stmt->execute([$taskId]);

    $this->notice = $success ? 'La tâche a bien été supprimée' : 'Erreur de traitement';
  }

  public function getNotice() {
    return $this->notice;
  }

  private function redirect_to($message) {
    header('Location: ?notice=' . $message);
    exit;
  }

  private function checkdata() {
    switch ($_REQUEST['mode']) {
      case 'add':
        return isset($_REQUEST['task-name']);
      case 'update':
        return isset($_REQUEST['id']);
      case 'delete':
        return isset($_REQUEST['id']);
      default:
        return true;
    }
  }
}

$taskManager = new TaskManager();
