<?php

function connectDB() {
  $db = new PDO('sqlite:tasks.db');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "CREATE TABLE IF NOT EXISTS tasks (
              id INTEGER PRIMARY KEY,
              task TEXT NOT NULL,
              date TEXT,
              priority INTEGER,
              status TEXT
            )";
  $db->exec($query);

  return $db;
}

function checkTaskDeadline($task) {
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

function controllerTask(){
  $notice = '';
  $request_mode = $_REQUEST['mode'] ?? null;

  if($request_mode) {
    switch ($request_mode) {
      case 'add':
        $notice = addTask($_REQUEST);
        break;
      case 'update':
        if($_REQUEST['id']) $notice = updateTask($_REQUEST['id'], $_REQUEST['status']);
        break;
      case 'delete':
        if($_REQUEST['id']) $notice = deleteTask($_REQUEST['id']);
        break;
    }
    if($notice == '') $notice = 'Un problème est survenu';
  }
  return $notice;
}

function getTasks() {
  $db = connectDB();
  $result = $db->query("SELECT * FROM tasks");
  return $result->fetchAll(PDO::FETCH_ASSOC);
}

function addTask($data) {
  $taskId = uniqid();
  $taskName = !empty($data['field-task']) ? $data['field-task'] : ($data['select-task'] ?? '');
  $taskPriority = $data['priority'];
  $taskDate = $data['date'];

  if (empty($taskName)) {
    redirect_to('Veuillez saisir une tâche');
  }

  $db = connectDB();
  $stmt = $db->prepare("INSERT INTO tasks (task, date, priority, status) VALUES (?,?,?,?)");
  $stmt->execute([$taskName, $taskDate, $taskPriority, 'wip']);

  $message = $succes ? 'La tâche a bien été ajoutée' : 'Erreur de traitement';
  redirect_to($message);
}

function updateTask($taskId, $currentStatus) {
  $status = $currentStatus === 'wip' ? 'finish' : 'wip';

  $db = connectDB();
  $stmt = $db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
  $success = $stmt->execute([$status, $taskId]);

  $message = $success ? 'La tâche a bien été mise à jour' : 'Erreur de traitement';
  redirect_to($message);
}

function deleteTask($taskId) {

  $db = connectDB();
  $stmt = $db->prepare("DELETE FROM tasks WHERE id = ?");
  $success = $stmt->execute([$taskId]);

  $message = $succes ? 'La tâche a bien été supprimée' : 'Erreur de traitement';
  redirect_to('La tâche a bien été supprimée');
}

function redirect_to($message){
  header('Location: ?notice='.$message);
  exit;
}