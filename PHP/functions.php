<?php

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
        if($_REQUEST['id']) $notice = updateTask($_REQUEST['id']);
        break;
      case 'delete':
        if($_REQUEST['id']) $notice = deleteTask($_REQUEST['id']);
        break;
    }
    if($notice == '') $notice = 'Un problème est survenu';
  }
  return $notice;
}

function addTask($data) {
  $taskName = !empty($data['field-task']) ? $data['field-task'] : ($data['select-task'] ?? '');
  $taskPriority = $data['priority'];
  $taskDate = $data['date'];

  if (empty($taskName)) {
    redirect_to('Veuillez saisir une tâche');
  }

  $taskId = uniqid();
  $_SESSION['tasks'][$taskId] = ['task' => $taskName, 'id' => $taskId, 'status' => 'wip', 'priority' => $taskPriority, 'date' => $taskDate];
  redirect_to('La tâche a bien été ajoutée');
}

function deleteTask($taskId) {
  unset($_SESSION['tasks'][$taskId]);
  redirect_to('La tâche a bien été supprimée');
}

function updateTask($taskId) {
  $_SESSION['tasks'][$taskId]['status'] = $_SESSION['tasks'][$taskId]['status'] === 'wip' ? 'finish' : 'wip';
  redirect_to('La tâche a bien été modifiée');
}

function redirect_to($message){
  header('Location: /?notice='.$message);
  exit;
}