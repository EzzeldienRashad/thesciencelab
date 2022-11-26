//create balls
function createBall() {
    let ball = document.createElement("div");
    let image = document.getElementsByClassName("image")[0];
    let diameter = 20;
    let hSpeed = Math.floor(Math.random() * 3 + 1);
    let vSpeed = Math.floor(Math.random() * 3 + 1) * [-1, 1][Math.floor(Math.random() * 2)];
    ball.className = "ball";
    ball.style.backgroundColor = "rgb(" + Math.floor(Math.random() * 256) + ", " + Math.floor(Math.random() * 256) + ", " + Math.floor(Math.random() * 256) + ")";
    image.append(ball);
    ball.style.top = Math.random() * image.clientHeight - diameter + "px";
    ball.style.left = "0";
    function move() {
        ball.style.left = ball.offsetLeft + scrollX + hSpeed + "px";
        ball.style.top = ball.offsetTop + scrollY + vSpeed + "px";
        if (ball.offsetTop + scrollY + diameter >= image.clientHeight || ball.offsetTop + scrollY <= 0) {
            vSpeed = -vSpeed;
        }
        if (ball.offsetLeft + scrollX > image.clientWidth) {
            ball.remove();
        }
        requestAnimationFrame(move);
    }
    requestAnimationFrame(move);
}
createBall();
let interval = setInterval(createBall, 1000);
document.addEventListener("visibilitychange", function () {
    if (document.visibilityState == "visible") {
        interval = setInterval(createBall, 1000);
    } else {
        clearInterval(interval);
    }
});

//add date to footer
document.getElementsByClassName("date")[0].innerHTML = 
  document.getElementsByClassName("current-date")[0].innerHTML == new Date().getFullYear() ? 
  "" : " - " + new Date().getFullYear();