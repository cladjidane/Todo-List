/**
 * Manage Task
 */
const form = document.querySelector("form");
const list = document.querySelector("ul");
const input = document.querySelector("form input");
const select = document.querySelector("form select");
let allItems = [];

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

function addTaskInBdd(task) {
  var formdata = new FormData();
  formdata.append("task", task);

  var requestOptions = {
    method: "POST",
    body: formdata,
    redirect: "follow",
  };

  fetch("https://cipa3:8890/add-task.php", requestOptions)
    .then((response) => response.json())
    .then((tasks) => tasks.map((task) => displayList(task)))
    .catch((error) => console.log("error", error));
}

function displayList(task) {
  const item = document.createElement("li");
  item.setAttribute("data-key", task.id);
  if (task.status === "finish") item.setAttribute("class", "ok");

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
  el.classList.toggle("ok");
}

function deleteItem(e) {
  const el = e.target.parentNode;
  if (el.getAttribute("class") !== "ok") alert("INTERDIT");
  else el.remove();
}

function notice(message) {
  alert(message);
}
