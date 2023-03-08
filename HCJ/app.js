/**
 * Load
 */
let datas
document.addEventListener("DOMContentLoaded", () => {
  fetch("https://cipa3:8890/")
    .then(function (response) {
      return response.json();
    })
    .then(function (json) {
      json.map(item => {
        displayList({text: item})
      })
    });

  if(datas) {
    console.log(datas)
  }
});

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
  let text = !input.value ? select.value : input.value.trim();

  if (text !== "") {
    displayList({
      text,
      id: Date.now(),
    });
    input.value = "";
    select.value = "";
  } else {
    alert("veuillez saisir une tâche !");
  }
}

function displayList(todo) {
  const item = document.createElement("li");
  item.setAttribute("data-key", todo.id);

  const input = document.createElement("input");
  input.setAttribute("type", "checkbox");
  input.addEventListener("click", itemOk);
  item.appendChild(input);

  const txt = document.createElement("span");
  txt.innerText = todo.text;
  item.appendChild(txt);

  const btn = document.createElement("button");
  btn.addEventListener("click", deleteItem);
  const img = document.createElement("img");
  img.setAttribute("src", "ressources/fermer.svg");
  btn.appendChild(img);
  item.appendChild(btn);

  list.appendChild(item);
  allItems.push(item);
  console.log(allItems);
}

function itemOk(e) {
  e.target.parentNode.classList.toggle("ok");
}

function deleteItem(e) {
  if (e.target.parentNode.getAttribute("class") !== "ok") alert("INTERDIT");
  else
    allItems.forEach((el) => {
      if (
        e.target.parentNode.getAttribute("data-key") ===
        el.getAttribute("data-key")
      ) {
        el.remove();
        allItems = allItems.filter((li) => li.dataset.key !== el.dataset.key);
      }
    });
}
