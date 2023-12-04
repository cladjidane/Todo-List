<?php

class TaskManager {
  private $db;

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
    $notice = '';
    $request_mode = $_REQUEST['mode'] ?? null;

    if ($request_mode) {
      switch ($request_mode) {
        case 'add':
          $notice = $this->addTask($_REQUEST);
          break;
        case 'update':
          if ($_REQUEST['id']) $notice = $this->updateTask($_REQUEST['id'], $_REQUEST['status']);
          break;
        case 'delete':
          if ($_REQUEST['id']) $notice = $this->deleteTask($_REQUEST['id']);
          break;
      }
      if ($notice == '') $notice = 'Un problème est survenu';
    }
    return $notice;
  }

  public function getTasks() {
    $result = $this->db->query("SELECT * FROM tasks");
    return $result->fetchAll(PDO::FETCH_ASSOC);
  }

  private function addTask($data) {
    $taskName = !empty($data['field-task']) ? $data['field-task'] : ($data['select-task'] ?? '');
    $taskPriority = $data['priority'];
    $taskDate = $data['date'];

    if (empty($taskName)) {
      $this->redirect_to('Veuillez saisir une tâche');
    }

    $stmt = $this->db->prepare("INSERT INTO tasks (task, date, priority, status) VALUES (?,?,?,?)");
    $success = $stmt->execute([$taskName, $taskDate, $taskPriority, 'wip']);

    $message = $success ? 'La tâche a bien été ajoutée' : 'Erreur de traitement';
    $this->redirect_to($message);
  }

  private function updateTask($taskId, $currentStatus) {
    $status = $currentStatus === 'wip' ? 'finish' : 'wip';

    $stmt = $this->db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $success = $stmt->execute([$status, $taskId]);

    $message = $success ? 'La tâche a bien été mise à jour' : 'Erreur de traitement';
    $this->redirect_to($message);
  }

  private function deleteTask($taskId) {
    $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
    $success = $stmt->execute([$taskId]);

    $message = $success ? 'La tâche a bien été supprimée' : 'Erreur de traitement';
    $this->redirect_to('La tâche a bien été supprimée');
  }

  private function redirect_to($message) {
    header('Location: ?notice=' . $message);
    exit;
  }
}

$taskManager = new TaskManager();
