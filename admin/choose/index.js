document.getElementsByClassName("buttons")[0].addEventListener("click", function (event) {
  if (event.target.tagName == "BUTTON") {
    grade = event.target.dataset.grade;
    fetch("index.php?grade=" + grade)
    .then(result => result.json())
    .then(function (units) {
      let buttons = document.createElement("div");
      buttons.className = "buttons";
      for (let unit of units) {
        let unitBtn = document.createElement("button");
        unitBtn.className = "button";
        unitBtn.textContent = unit;
        unitBtn.setAttribute("data-unit", unit);
        buttons.append(unitBtn);
      }
      document.getElementsByClassName("buttons")[0].style.opacity = "0";
      document.getElementsByClassName("buttons")[0].remove();
      document.getElementsByTagName("main")[0].append(buttons);
      buttons.addEventListener("click", function (event) {
        if (event.target.tagName == "BUTTON") {
          location.href = "add.php?grade=" + grade + "&unit=" + event.target.dataset.unit;
        }
      });
    });
  }
});