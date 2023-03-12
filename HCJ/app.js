/**
 * Load
 */
document.addEventListener("DOMContentLoaded", () => {
  fetchData("https://cipa3:8890/tasks.php", "GET", null, function (tasks) {
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
  const datas = {
    id: el.getAttribute("data-key"),
    status: el.getAttribute("class") !== "ok" ? "finish" : "pending",
  };

  fetchData("https://cipa3:8890/update-task.php", "POST", datas, () =>
    el.classList.toggle("ok")
  );
}

function deleteItem(e) {
  const el = e.target.parentNode;

  if (el.getAttribute("class") !== "ok") {
    alert("INTERDIT");
    return;
  }

  fetchData(
    "https://cipa3:8890/delete-task.php",
    "POST",
    { id: el.getAttribute("data-key") },
    () => notice(result.message)
  );

  el.remove();
}

function addTaskInBdd(task) {
  fetchData("https://cipa3:8890/add-task.php", "POST", task, (tasks) =>
    tasks.map((task) => displayList(task))
  );
}

function notice(message) {
  alert(message);
}

function fetchData(url, method = "GET", datas, callBack) {
  let formdata;
  if (datas) {
    formdata = new FormData();
    for (var key in datas) {
      formdata.append(key, datas[key]);
    }
  }

  var requestOptions = {
    method: method,
    body: formdata,
    redirect: "follow",
  };

  fetch(url, requestOptions)
    .then((response) => response.json())
    .then(callBack)
    .catch((error) => console.log("error", error));
}
