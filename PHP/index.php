<?php 

include('db.class.php');

$db = new Db();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    switch ($_REQUEST['mode']) {
        case 'add':
            $task = $_REQUEST['field-task'] ? $_REQUEST['field-task'] : $_REQUEST['select-task'];
            $db->addTask($task);
            break;
        
        case 'update':
            $status = $_REQUEST['status'] == "on" ? "finish" : "pending";
            $db->updateTask($_REQUEST['id'], $status);
            break;
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    switch ($_REQUEST['mode']) {
        case 'delete':
            $db->deleteTask($_REQUEST['id']);
            break;
    }
}

$tasks = $db->getTasks();

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

    <form class="form-add" method="post">
      <label for="item">Ajouter une tâche</label>
      <div class="fields">
        <input name="field-task" type="text" id="item" placeholder="Intitulé de la tâche" />
        ou
        <select name="select-task" >
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
                <li data-key="<?php echo $task->id; ?>" class="<?php echo $task->status == "finish" ? "ok": ""; ?>">
                    <form class="form-checked" method="post">
                        <input <?php echo $task->status == "finish" ? "checked": ""; ?> type="checkbox" name="status"> 
                        <input type="hidden" name="mode" value="update" />
                        <input type="hidden" name="id" value="<?php echo $task->id; ?>" />
                        <button>Valider</button>
                    </form>
                    <span><?php echo $task->task; ?></span>
                    <a href="?mode=delete&id=<?php echo $task->id; ?>" class="btdelete"></a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <script src="app.js"></script>
  </body>
</html>
