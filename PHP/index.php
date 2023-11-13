<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

$notice = '';
$_SESSION['tasks'] = $_SESSION['tasks'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['mode'] === 'add') {
  $taskName = !empty($_POST['field-task']) ? $_POST['field-task'] : ($_POST['select-task'] ?? '');
  
  if (empty($taskName)) {
    $notice = "Le nom de la tâche est vide !";
  } else {
    $taskId = uniqid();
    $_SESSION['tasks'][$taskId] = ['task' => $taskName, 'id' => $taskId, 'status' => 'wip'];
    $notice = "Tâche ajoutée avec succès !";
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mode'], $_GET['id'])) {
  $taskId = $_GET['id'];
  if ($_GET['mode'] === 'update') {
    $_SESSION['tasks'][$taskId]['status'] = $_SESSION['tasks'][$taskId]['status'] === 'wip' ? 'finish' : 'wip';
  } elseif ($_GET['mode'] === 'delete' && $_SESSION['tasks'][$taskId]['status'] === 'finish') {
    unset($_SESSION['tasks'][$taskId]);
  }
}

$tasks = $_SESSION['tasks'];
?>


<!DOCTYPE html>
<html lang="fr">

<head>  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Todo</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <h1>Todo List</h1>
  <h2>Gestionnaire de tâches</h2>

  <form class="form-add" method="post" action="">

    <label for="item">Ajouter une tâche</label>
    <div class="fields">
      <input name="field-task" type="text" id="item" placeholder="Intitulé de la tâche" />
      ou
      <select name="select-task">
        <option value="">Choisir une tâche</option>
        <option value="Passer le balai">Passer le balai</option>
        <option value="Saluer le boss">Saluer le boss</option>
        <option value="Couper l'ordi">Couper l'ordi</option>
      </select>
    </div>
    <input type="hidden" name="mode" value="add" />

    <button type="submit">Ajouter</button>

  </form>

  <?php if($notice != null) : ?>
    <div class="notice"><?php echo $notice; ?></div>
  <?php endif; ?>

  <ul class="list-todo">
    <?php if($tasks) : ?>
    <?php foreach($tasks as $key => $task) : ?>

    <li class="<?php echo $task['status'] == "finish" ? "ok": ""; ?>">

      <a  href="?mode=update&id=<?php echo $task['id']; ?>">
        <input
          <?php echo $task['status'] == "finish" ? "checked": ""; ?>
          type="checkbox"
          name="status"
        />
      </a>

      <span class="<?php echo $task['status'] == "finish" ? "finished": "wip"; ?>">
        <?php echo $task['task']; ?> (<?php echo $task['id']; ?> - <?php echo $task['status']; ?>)
      </span>

      <a href="?mode=delete&id=<?php echo $task['id']; ?>" class="btdelete"></a>

    </li>

    <?php endforeach; ?>
    <?php endif; ?>
  </ul>


</body>

</html>