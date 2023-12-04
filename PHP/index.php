<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include('functions.php');

$notice = $taskManager->controllerTask();

$tasks = $taskManager->getTasks();
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
  <h3><?php echo date("d-m-Y"); ?></h3>

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
    <div class="fields">
    <select name="priority">
        <option value="1">Urgent</option>
        <option value="2">Moyen</option>
        <option value="3">Mineur</option>
      </select>
      -
      <input name="date" type="date" id="date" value="<?php echo date("Y-m-d"); ?>" placeholder="Date de prise en charge" />
    </div>
    <input type="hidden" name="mode" value="add" />

    <button type="submit">Ajouter</button>

  </form>

  <?php if($notice != null || isset($_GET['notice'])) : ?>
    <div class="notice"><?php echo $notice ? $notice : $_GET['notice']; ?></div>
  <?php endif; ?>

  <ul class="list-todo">
    <?php if($tasks) : ?>
    <?php
      usort($tasks, function($a, $b) {
        return $a['priority'] - $b['priority'];
      });
    ?>
    <?php foreach($tasks as $key => $task) : ?>

    <li class="<?php echo $task['status'] == "finish" ? "ok": ""; ?>">

      <a  href="?mode=update&id=<?php echo $task['id']; ?>&status=<?php echo $task['status']; ?>">
        <input
          <?php echo $task['status'] == "finish" ? "checked": ""; ?>
          type="checkbox"
          name="status"
        />
      </a>

      <span class="<?php echo $task['status'] == "finish" ? "finished": "wip"; ?>">
        [<?php echo $task['priority']; ?>]
        <?php echo $task['task']; ?>
        <?php echo $taskManager->checkTaskDeadline($task); ?>
      </span>

      <a href="?mode=delete&id=<?php echo $task['id']; ?>" class="btdelete"></a>

    </li>

    <?php endforeach; ?>
    <?php endif; ?>
  </ul>


</body>

</html>