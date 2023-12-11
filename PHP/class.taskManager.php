<?php

// La classe TaskManager est conçue pour gérer des tâches stockées dans une base de données SQLite.
class TaskManager {
  private $db;      // Propriété pour stocker l'objet PDO de connexion à la base de données.
  private $notice;  // Propriété pour stocker des messages ou des notifications.

  // Le constructeur initialise la connexion à la base de données et crée la table des tâches si elle n'existe pas.
  public function __construct() {
    // Connexion à la base de données SQLite.
    $this->db = new PDO('sqlite:tasks.db');
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table 'tasks' si elle n'existe pas.
    $query = "CREATE TABLE IF NOT EXISTS tasks (
                    id INTEGER PRIMARY KEY,
                    task TEXT NOT NULL,
                    date TEXT,
                    priority INTEGER,
                    status TEXT
                  )";
    $this->db->exec($query);

    // Appel de la méthode controllerTask pour gérer les actions des tâches.
    $this->controllerTask();
  }

  // Méthode pour récupérer les tâches en fonction de leur statut.
  public function getTasks($status = "wip") {
    // Exécution de la requête pour récupérer les tâches.
    $result = $this->db->query("SELECT * FROM tasks WHERE status='$status' ");
    $tasks = $result->fetchAll(PDO::FETCH_ASSOC);

    // Tri des tâches en fonction de leur priorité.
    usort($tasks, function ($a, $b) {
      return $a['priority'] - $b['priority'];
    });

    return $tasks;
  }

  // Méthode pour vérifier la date limite d'une tâche et renvoyer un format de date approprié.
  public function checkTaskDeadline($task) {
    // Création d'objets DateTime pour comparer les dates.
    $currentDate = new DateTime();
    $taskDate = DateTime::createFromFormat('Y-m-d', $task['date']);
    $dateFormatted = $taskDate->format('d-m-Y');

    // Vérification si la tâche est en retard.
    if ($taskDate < $currentDate) {
      $daysPassed = $currentDate->diff($taskDate)->format('%a');
      $daysLimits = ['1' => 1, '2' => 3, '3' => 7];

      // Retour d'un message indiquant le retard.
      if ($daysPassed > $daysLimits[$task['priority']]) {
        return "<span><span class=\"late\"></span>$dateFormatted ($daysPassed jour" . ($daysPassed > 1 ? 's' : '') . ")</span>";
      }
    }

    // Retour d'un message indiquant que la tâche est à jour.
    return "<span><span class=\"on-time\"></span>$dateFormatted</span>";
  }

  // Méthode pour contrôler les actions sur les tâches en fonction de la requête HTTP.
  public function controllerTask() {
    // Récupération du mode de la requête (ajout, mise à jour, suppression).
    $request_mode = $_REQUEST['mode'] ?? null;

    // Traitement en fonction du mode de la requête.
    if ($request_mode) {
      switch ($request_mode) {
        case 'add':
          $this->addTask($_REQUEST);
          break;
        case 'update':
          $this->updateTask($_REQUEST['id'], $_REQUEST['status']);
          break;
        case 'delete':
          $this->deleteTask($_REQUEST['id']);
          break;
      }
    }
  }

  // Méthode privée pour ajouter une tâche à la base de données.
  private function addTask($data) {
    // Vérification des données soumises.
    if ($this->checkData()) {
      // Récupération des données de la tâche.
      $taskName = !empty($data['field-task']) ? $data['field-task'] : ($data['select-task'] ?? '');
      $taskPriority = $data['priority'];
      $taskDate = $data['date'];

      // Préparation et exécution de la requête d'insertion.
      $stmt = $this->db->prepare("INSERT INTO tasks (task, date, priority, status) VALUES (?,?,?,?)");
      $success = $stmt->execute([$taskName, $taskDate, $taskPriority, 'wip']);

      // Mise à jour du message de notification.
      $this->notice = $success ? 'La tâche a bien été ajoutée' : 'Erreur de traitement';
    } else {
      $this->notice = 'Erreur';
    }
  }

  // Méthode privée pour mettre à jour le statut d'une tâche.
  private function updateTask($taskId, $currentStatus) {
    // Changement du statut de la tâche.
    $status = $currentStatus === 'wip' ? 'finish' : 'wip';

    // Préparation et exécution de la requête de mise à jour.
    $stmt = $this->db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $success = $stmt->execute([$status, $taskId]);

    // Mise à jour du message de notification.
    $this->notice = $success ? 'La tâche a bien été mise à jour' : 'Erreur de traitement';
  }

  // Méthode privée pour supprimer une tâche.
  private function deleteTask($taskId) {
    // Préparation et exécution de la requête de suppression.
    $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
    $success = $stmt->execute([$taskId]);

    // Mise à jour du message de notification.
    $this->notice = $success ? 'La tâche a bien été supprimée' : 'Erreur de traitement';
  }

  // Méthode publique pour obtenir le message de notification.
  public function getNotice() {
    return $this->notice;
  }

  // Méthode privée pour rediriger vers une autre page avec un message.
  private function redirect_to($message) {
    header('Location: ?notice=' . $message);
    exit;
  }

  // Méthode privée pour vérifier les données soumises dans la requête.
  private function checkdata() {
    // Vérification en fonction du mode de la requête.
    switch ($_REQUEST['mode']) {
      case 'add':
        return isset($_REQUEST['field-task']);
      case 'update':
        return isset($_REQUEST['id']);
      case 'delete':
        return isset($_REQUEST['id']);
      default:
        return true;
    }
  }
}

// Création d'une instance de TaskManager pour utilisation.
$taskManager = new TaskManager();
?>
