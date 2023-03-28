<?php 

include('db.class.php');

$db = new Db();
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

    <form>
      <label for="item">Ajouter une tâche</label>
      <div class="fields">
        <input type="text" id="item" placeholder="Intitulé de la tâche" />
        ou
        <select>
          <option value="">Choisir une tâche</option>
          <option value="Passer le balai">Passer le balai</option>
          <option value="Saluer le boss">Saluer le boss</option>
          <option value="Couper l'ordi">Couper l'ordi</option>
        </select>
      </div>

      <button type="submit">Ajouter</button>
    </form>

    <ul class="list-todo">
        <?php if($tasks) : ?>
            <?php foreach($tasks as $key => $task) : ?>
                <li data-key="<?php echo $task->id; ?>" class=""><input type="checkbox"><span><?php echo $task->task; ?></span><button></button></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <script src="app.js"></script>
  </body>
</html>
