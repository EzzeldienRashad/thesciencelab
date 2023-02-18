//get units
document.getElementsByClassName("buttons")[0].addEventListener("click", function (event) {
    if (event.target.tagName == "BUTTON") {
        grade = event.target.dataset.grade;
        fetch("index.php?grade=" + grade)
        .then(result => result.json())
        .then(function (units) {
            let buttons = document.createElement("div");
            buttons.className = "row g-2"
            for (let unit of units) {
                let unitBtnCont = document.createElement("div");
                unitBtnCont.className = "col-md-6";
                let unitBtn = document.createElement("button");
                unitBtn.className="btn btn-primary p-3 w-100 h-100"
                unitBtn.textContent = unit;
                unitBtn.setAttribute("data-unit", unit);
                unitBtnCont.append(unitBtn);
                buttons.append(unitBtnCont);
            }
            document.getElementsByClassName("buttons")[0].style.opacity = "0";
            document.getElementsByClassName("buttons")[0].remove();
            document.getElementsByTagName("main")[0].append(buttons);
            buttons.addEventListener("click", function (event) {
                if (event.target.tagName == "BUTTON") {
                    location.href = "manage.php?grade=" + grade + "&unit=" + event.target.dataset.unit;
                }
            });
        });
    }
});
//reload on button click
document.getElementsByClassName("fa-rotate-left")[0].addEventListener("click", function () {
    location.reload();
});