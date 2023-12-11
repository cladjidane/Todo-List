<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclusion de la classe TaskManager
include('class.taskManager.php');

// Récupération du message de notification (si présent) depuis TaskManager
$notice = $taskManager->getNotice();

// Récupération des tâches en cours ('wip') et terminées ('finish') depuis TaskManager
$tasksWip = $taskManager->getTasks('wip');
$tasksFinish = $taskManager->getTasks('finish');
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

  <?php if ($notice != null || isset($_GET['notice'])) : ?>
    <div class="notice"><?php echo $notice ? $notice : $_GET['notice']; ?></div>
  <?php endif; ?>

  <div class="lists-tasks">
    <div>
      <h4>Tâches en cours</h4>
      <ul class="list-todo">
        <?php if ($tasksWip) : ?>
          <?php foreach ($tasksWip as $key => $task) : ?>

            <li class="<?php echo $task['status'] == "finish" ? "ok" : ""; ?>">
              <a href="?mode=update&id=<?php echo $task['id']; ?>&status=<?php echo $task['status']; ?>">
                <input <?php echo $task['status'] == "finish" ? "checked" : ""; ?> type="checkbox" name="status" />
              </a>
              <span class="<?php echo $task['status'] == "finish" ? "finished" : "wip"; ?>">
                [<?php echo $task['priority']; ?>]
                <?php echo $task['task']; ?>
                <?php echo $taskManager->checkTaskDeadline($task); ?>
              </span>
            </li>

          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>

    <div>
      <h4>Tâches terminées</h4>
      <ul class="list-todo">
        <?php if ($tasksFinish) : ?>
          <?php foreach ($tasksFinish as $key => $task) : ?>

            <li class="<?php echo $task['status'] == "finish" ? "ok" : ""; ?>">
              <span class="finished">
                [<?php echo $task['priority']; ?>]
                <?php echo $task['task']; ?>
              </span>
              <a href="?mode=delete&id=<?php echo $task['id']; ?>" class="btdelete"></a>
            </li>

          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>

</body>

</html>