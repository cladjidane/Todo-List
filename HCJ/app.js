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
    displayList({});

    input.value = "";
    select.value = "";
  } else {
    alert("veuillez saisir une tâche !");
  }
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
  // A faire - changer le status en BDD
  el.classList.toggle("ok")
}

function deleteItem(e) {
  const el = e.target.parentNode;
  // A faire - suppression en BDD
  if (el.getAttribute("class") !== "ok") alert("INTERDIT");
  else el.remove();
}
