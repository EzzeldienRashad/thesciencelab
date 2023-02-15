//import questions
let lessons = document.getElementsByTagName("section")[0];
lessons.addEventListener("click", function (event) {
    if (event.target.tagName == "BUTTON") {     
        let filename = event.target.dataset.unit;
        let buttonsCont = document.getElementsByClassName("buttons")[1];
        fetch("choose.php?filename=" + filename)
        .then(number => number.text())
        .then(function (number) {
            if (Number(number) < 5) {
                let button = document.createElement("button");
                button.textContent = number;
                button.setAttribute("data-num", number);
                buttonsCont.append(button);
            } else {
                for (i = 5; i < number - (number % 5); i += 5) {
                    let button = document.createElement("button");
                    button.textContent = i;
                    buttonsCont.append(button);
                }
                let button = document.createElement("button");
                button.textContent = number;
                button.setAttribute("data-num", number);
                buttonsCont.append(button);
            }
            lessons.style.height = lessons.offsetHeight + "px";
            setTimeout(function () {
                lessons.style.height = "0";
                document.getElementsByTagName("section")[1].style.height = "500px";
            }, 10);
            buttonsCont.addEventListener("click", function (event) {
                if (event.target.tagName == "BUTTON" && event.target.dataset.num <= number) {
                        fetch("choose.php?filename=" + filename + "&number=" + number)
                        .then(response => response.json())
                        .then(function (questions) {
                            document.getElementsByTagName("section")[1].style.opacity = 0;
                            setTimeout(function () {
                                document.getElementsByTagName("section")[1].remove();
                                lessons.remove();
                                startTest(questions);
                            }, 1000);
                        })
                }
            });
        })
    }
});

function startTest(questions) {
    let rightAudio = document.getElementById("rightAudio");
    let wrongAudio = document.getElementById("wrongAudio");
    let test = document.createElement("div");
    test.className = "test";
    document.getElementsByTagName("main")[0].append(test);
    let next = document.createElement("i");
    next.className = "next fa-solid fa-right-long";
    test.append(next);
    let rightPic = document.createElement("img");
    rightPic.src = "../../images/choose/right.webp";
    rightPic.alt = "RIGHT!";
    rightPic.className = "right-wrong";
    rightPic.width = "200";
    rightPic.height = "200";
    test.append(rightPic);
    let wrongPic = document.createElement("img");
    wrongPic.src = "../../images/choose/wrong.webp";
    wrongPic.alt = "WRONG!";
    wrongPic.className = "right-wrong";
    wrongPic.width = "200";
    wrongPic.height = "200";
    test.append(wrongPic);
    let score = 0;
    let maxScore = questions.length;
    let currentQuestionNumber = 1;
    for (let question of questions) {
        let currentQuestion = document.createElement("div");
        currentQuestion.className = "question";
        test.append(currentQuestion);
        let title = document.createElement("h2");
        title.textContent = question[0];
        currentQuestion.append(title);
        let answers = document.createElement("div");
        answers.className = "answers";
        currentQuestion.append(answers);
        let letters = ["a) ", "b) ", "c) ", "d) "]
        for (let i = 0; i < 4; i++) {
            let answer = document.createElement("button");
            answer.className = "answer";
            answer.textContent = letters[i] + question[1][i];
            answer.setAttribute("data-number", i + 1)
            answers.append(answer);
        }
        //check answer
        answers.addEventListener("click", function answerClick(event) {
            if (event.target.tagName == "BUTTON") {
                if (event.target.getAttribute("data-number") == question[2]) { // If answer is right
                    score++;
                    rightPic.style.display = "inline";
                    rightAudio.play();
                    event.target.style.border = "5px solid green";
                } else {
                    wrongPic.style.display = "inline";
                    wrongAudio.play();
                    event.target.style.border = "5px solid red";
                    answers.querySelector(".answer:nth-child(" + Number(question[2]) + ")").style.border = "5px solid green";
                }
                answers.removeEventListener("click", answerClick);
                next.style.width = "auto";
                next.style.height = "auto";
                next.addEventListener("click", function toNextQuestion() {
                    if (currentQuestionNumber >= maxScore) {
                        result();
                        return;
                    }
                    test.style.marginLeft = test.offsetLeft - document.documentElement.clientWidth + "px";
                    rightPic.style.display = "";
                    wrongPic.style.display = "";
                    next.style.width = "";
                    next.style.height = "";
                    currentQuestionNumber++;
                    next.removeEventListener("click", toNextQuestion);
                });
            }
        });
    }
    function result() {
        test.style.opacity = "0";
        setTimeout(function () {
            test.remove();
            let colors = ["maroon", "red", "red", "red", "orange", "orange", "lightgreen", "lightgreen", "green", "green", "darkgreen"];
            let scoreWords = ["very bad", "bad", "bad", "bad", "moderate", "moderate", "good", "good", "very good", "very good", "excellent"];
            let descriptions = [
                "Your score isn't very good. You don't seem to have studied your lessons well. Study again, the come here to measure how your score has become one more time.",
                "You got most of the questions wrong. This indecates that you didn't study hard. You should study with more focus and memorize the information well, then come here and measure your score again.",
                "You got most of the questions wrong. This indecates that you didn't study hard. You should study with more focus and memorize the information well, then come here and measure your score again.",
                "You got most of the questions wrong. This indecates that you didn't study hard. You should study with more focus and memorize the information well, then come here and measure your score again.",
                "You got moderate. This means that you have a good understanding of the lessons, but you still mess a lot of questions. You need to revise the lessons and solve more tests before comming back again.",
                "You got moderate. This means that you have a good understanding of the lessons, but you still mess a lot of questions. You need to revise the lessons and solve more tests before comming back again.",
                "Good work! You got most of the questions right! This is a good indicator that you understand the lesson, but you still miss some information. You should solve more tests and revise from time to time.",
                "Good work! You got most of the questions right! This is a good indicator that you understand the lesson, but you still miss some information. You should solve more tests and revise from time to time.",
                "Well done! That was awesome! You got almost all of the questions right. You're on your way to becoming a science expert. All you need is more practice and solving quizes. Never give up!",
                "Well done! That was awesome! You got almost all of the questions right. You're on your way to becoming a science expert. All you need is more practice and solving quizes. Never give up!",
                "WOW! That's exceptional! You got all of the questions right! You're on the right way to becoming a great scientist. Do not waste your abilities. Now, it's time to discover more in the world of science and help other people solve their problems. Good luck!"
            ]
            let scoreInt = Math.floor(score / maxScore * 10);
            let resultPage = document.createElement("div");
            resultPage.className = "result-page";
            document.getElementsByTagName("main")[0].append(resultPage);
            let cOuter = document.createElement("div");
            cOuter.className = "c-outer";
            cOuter.style.backgroundImage = "conic-gradient(" + colors[scoreInt] + 
            " 0%, " + colors[scoreInt] + " " + score / maxScore * 100 + "%, white " + 
            score / maxScore * 100 + "%, white 100%";
            resultPage.append(cOuter);
            let cInner = document.createElement("div");
            cInner.className = "c-inner";
            cInner.innerHTML = "<span>" + score + "/" + maxScore + "</span>"
            cInner.style.color = colors[scoreInt];
            cOuter.append(cInner);
            let scoreWord = document.createElement("h1");
            scoreWord.style.color = colors[scoreInt];
            scoreWord.textContent = scoreWords[scoreInt];
            resultPage.append(scoreWord);
            let description = document.createElement("p");
            description.textContent = descriptions[scoreInt];
            resultPage.append(description);
            let continueBtn = document.createElement("a");
            continueBtn.href = "index.php";
            continueBtn.className = "continue-btn";
            continueBtn.textContent = "continue"
            resultPage.append(continueBtn);
        }, 1000);
    }
}
//add date to footer
document.getElementsByClassName("date")[0].innerHTML = 
  document.getElementsByClassName("current-date")[0].innerHTML == new Date().getFullYear() ? 
  "" : " - " + new Date().getFullYear();