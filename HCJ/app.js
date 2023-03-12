/**
 * Manage Task
 */
const form = document.querySelector("form");
const list = document.querySelector("ul");
const input = document.querySelector("form input");

form.addEventListener("submit", submitTask);

function submitTask(event) {
  event.preventDefault();
  let task = input.value.trim();

  if (task !== "") {
    displayList({ task: task, id: Date.now() });
    input.value = "";
  } else {
    alert("veuillez saisir une tâche !");
  }
}

function displayList(task) {
  // Création du LI
  const item = document.createElement("li");
  item.setAttribute("data-key", task.id);
  if (task.status === "finish") item.setAttribute("class", "ok");

  // Création de la case à cocher
  const input = document.createElement("input");
  input.setAttribute("type", "checkbox");
  input.addEventListener("click", updateItem);
  item.appendChild(input);

  if (task.status === "finish") {
    item.setAttribute("class", "ok");
    input.setAttribute("checked", "checked");
  }

  // Création du span pour le texte de la tâche 
  const txt = document.createElement("span");
  txt.innerText = task.task;
  item.appendChild(txt);

  // Création du bouton de suppression
  const btn = document.createElement("button");
  btn.addEventListener("click", deleteItem);

  // Création de l'image dans le bouton
  const img = document.createElement("img");
  img.setAttribute("src", "ressources/fermer.svg");
  btn.appendChild(img);
  item.appendChild(btn);

  // Infection de l'ensemble dans la liste
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
