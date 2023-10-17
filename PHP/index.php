<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mode']) && $_POST['mode'] == 'add') {
  $taskName = $_POST['field-task'] ?? '';
  $selectedTask = $_POST['select-task'] ?? '';

  if (empty($taskName) && empty($selectedTask)) {
    $notice = "Le nom de la tâche est vide !";
  } else {
    if (!isset($_SESSION['tasks'])) {
      $_SESSION['tasks'] = [];
    }

    if (!empty($taskName)) {
      $_SESSION['tasks'][] = $taskName;
    } elseif (!empty($selectedTask)) {
      $_SESSION['tasks'][] = $selectedTask;
    }

    $notice = "Tâche ajoutée avec succès.";
  }
}

$tasks = $_SESSION['tasks'];
var_dump($tasks);
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

  <ul class="list-todo">
    <?php if($tasks) : ?>
    <?php foreach($tasks as $key => $task) : ?>

    <li class="<?php echo $task['status'] == "finish" ? "ok": ""; ?>">
      <input <?php echo $task['status'] == "finish" ? "checked": ""; ?> type="checkbox" name="status">
      <span>
        <?php echo $task['task']; ?>
      </span>
      <a href="?mode=delete&id=<?php echo $task['id']; ?>" class="btdelete">
      </a>
    </li>

    <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</body>

</html>