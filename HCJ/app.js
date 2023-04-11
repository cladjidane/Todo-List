/**
 * Manage Task
 */
const form = document.querySelector("form");
const list = document.querySelector("ul");
const input = document.querySelector("form input");
const select = document.querySelector("form select");

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

    // common tasks
    fetch("http://cipa3/common-tasks.php")
    .then(function (response) {
      return response.json();
    })
    .then(function (commonTasks) {
      commonTasks.map((task, i) => {
        if(i > 5) return
        let option = document.createElement("option");
        option.setAttribute("value", task.task);
        option.innerText = task.task
        select.appendChild(option);
      });
    });
});

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

  var requestOptions = {
    method: "GET",
    redirect: "follow",
  };

  fetch(
    "http://cipa3/update-task.php?id=" + id + "&status=" + status,
    requestOptions
  )
    .then((response) => response.json())
    .then(() => el.classList.toggle("ok"))
    .catch((error) => console.log("error", error));
}

function deleteItem(e) {
  const el = e.target.parentNode;
  const id = el.getAttribute("data-key");

  if (el.getAttribute("class") !== "ok") {
    alert("INTERDIT");
    return;
  }

  var requestOptions = {
    method: "GET",
    redirect: "follow",
  };

  fetch("http://cipa3/delete-task.php?id=" + id, requestOptions)
    .then((response) => response.json())
    .then((result) => notice(result.message))
    .catch((error) => console.log("error", error));

  el.remove();
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
