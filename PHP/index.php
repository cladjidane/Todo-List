

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
//var_dump($_SESSION);
$notice = null;

// ADD TASK
if (
  $_SERVER['REQUEST_METHOD'] == 'POST' && // On est en POST ?
  isset($_POST['mode']) && // Y a t-il une information de mode
  $_POST['mode'] == 'add' // Oui, est-ce add ?
) { // Si oui ↓↓↓

  $taskName = $_POST['field-task'] ?? '';
  $selectedTask = $_POST['select-task'] ?? '';

  if (empty($taskName) && empty($selectedTask)) {
    $notice = "Le nom de la tâche est vide !";
  } else {
      if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [];
      }

      if (!empty($taskName)) {
        $newTask = array('task' => $taskName, 'id' => count($_SESSION['tasks']) + 1, 'status' => 'wip');
      } elseif (!empty($selectedTask)) {
        $newTask = array('task' => $selectedTask, 'id' => count($_SESSION['tasks']) + 1, 'status' => 'wip');
      }

      $_SESSION['tasks'][] = $newTask;

    $notice = "Tâche ajoutée avec succès !";
  }
}

// UPDATE TASK
if (isset($_GET['mode']) && $_GET['mode'] == 'update' && isset($_GET['id'])) {
  $taskId = $_GET['id'];
  $taskToUpdate = &$_SESSION['tasks'][$taskId - 1];

  $taskToUpdate['status'] = $taskToUpdate['status'] == 'wip' ? 'finish' : 'wip';
}

// DELETE TASK
if (isset($_GET['mode']) && $_GET['mode'] == 'delete' && isset($_GET['id'])) {
  $taskId = $_GET['id'];
  if (
    isset($_SESSION['tasks'][$taskId - 1]) &&
    $_SESSION['tasks'][$taskId - 1]['status'] == 'finish'
  ) {
      array_splice($_SESSION['tasks'], $taskId - 1, 1);
      foreach ($_SESSION['tasks'] as $index => &$task) {
        $task['id'] = $index + 1;
      }
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