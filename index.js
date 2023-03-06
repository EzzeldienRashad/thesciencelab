//create balls
function createBall() {
    if (document.querySelectorAll(".ball").length > 15) return;
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
        ball.style.left = ball.offsetLeft + hSpeed + "px";
        ball.style.top = ball.offsetTop + vSpeed + "px";
        if (ball.offsetTop + diameter >= image.clientHeight || ball.offsetTop <= 0) {
            vSpeed = -vSpeed;
        }
        if (ball.offsetLeft > image.clientWidth) {
            ball.remove();
        }
        requestAnimationFrame(move);
    }
    requestAnimationFrame(move);
}
createBall();
let interval = setInterval(createBall, 1000);

//add date to footer
document.getElementsByClassName("date")[0].innerHTML = 
  document.getElementsByClassName("current-date")[0].innerHTML == new Date().getFullYear() ? 
  "" : " - " + new Date().getFullYear();