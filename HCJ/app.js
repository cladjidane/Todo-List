/**
 * Load
 */
document.addEventListener("DOMContentLoaded", () => {
  fetch("http://cipa3/tasks.php")
    .then(function (response) {
      return response.json();
    })
    .then(function (tasks) {
      tasks.map((task) => {
        displayList(task);
      });
    });
});

/**
 * Manage Task
 */
const form = document.querySelector("form");
const list = document.querySelector("ul");
const input = document.querySelector("form input");
const select = document.querySelector("form select");

form.addEventListener("submit", submitTask);

function submitTask(event) {
  event.preventDefault();
  let task = !input.value ? select.value : input.value.trim();

  if (task !== "") {
    addTaskInBdd(task);

    input.value = "";
    select.value = "";
  } else {
    alert("veuillez saisir une tâche !");
  }
}

function displayList(task) {
  const item = document.createElement("li");
  item.setAttribute("data-key", task.id);

  const input = document.createElement("input");
  input.setAttribute("type", "checkbox");
  input.addEventListener("click", updateItem);
  item.appendChild(input);

  if (task.status === "finish") {
    item.setAttribute("class", "ok");
    input.setAttribute("checked", "checked");
  }

  const txt = document.createElement("span");
  txt.innerText = task.task;
  item.appendChild(txt);

  const btn = document.createElement("button");
  btn.addEventListener("click", deleteItem);
  const img = document.createElement("img");
  img.setAttribute("src", "ressources/fermer.svg");
  btn.appendChild(img);
  item.appendChild(btn);

  list.appendChild(item);
}

function updateItem(e) {

  const el = e.target.parentNode;
  const id = el.getAttribute("data-key");
  const status = el.getAttribute("class") !== "ok" ? "finish" : "pending";

  /**
   * ...
   * 
   * TODO
   * 
   * Aide : requête très proche de celle de "addTaskInBdd"
   * Mais en GET et non en POST
   * En GET il n'y a pas de formData, mais des paramètres passés directement dans l'URL
   * Ex page.php?id=12&mode=update
   * 
   * Attention à bien conserver requestOptions (mais sans formData et avec le bon verbe HTTP)
   * 
   * Une fois la reqête effectuée vous devez simplement definir la class du li pour définir son état
   * Ceci est déjà dans le code original vu ensemble
   */
}

function deleteItem(e) {
  /**
   * ...
   * 
   * TODO
   * 
   * A vous de creuser :)
   */
}

function addTaskInBdd(task) {
  var formdata = new FormData();
  formdata.append("task", task);

  var requestOptions = {
    method: "POST",
    body: formdata,
    redirect: "follow",
  };

  fetch("http://cipa3/add-task.php", requestOptions)
    .then((response) => response.json())
    .then((tasks) => tasks.map((task) => displayList(task)))
    .catch((error) => console.log("error", error));
}

function notice(message) {
  alert(message);
}
