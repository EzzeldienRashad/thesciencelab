//import questions
let lessons = document.getElementsByTagName("section")[0];
let grade = new URL(location.href).searchParams.get("grade");
lessons.addEventListener("click", function (event) {
    if (event.target.tagName == "BUTTON") {     
        let filename = event.target.dataset.unit;
        let buttonsCont = document.getElementsByClassName("buttons")[1];
        fetch("complete.php?grade=" + grade + "&filename=" + filename)
        .then(number => number.text())
        .then(function (number) {
            for (i = 1; i <= Math.floor(number / 5); i++) {
                let button = document.createElement("button");
                button.textContent = i;
                button.setAttribute("data-num", i);
                buttonsCont.append(button);
            }
            lessons.style.height = lessons.offsetHeight + "px";
            setTimeout(function () {
                lessons.style.height = "0";
                document.getElementsByTagName("section")[1].style.height = "500px";
            }, 10);
            buttonsCont.addEventListener("click", function (event) {
                if (event.target.tagName == "BUTTON" && Number(event.target.dataset.num) <= number) {
                        fetch("complete.php?grade=" + grade + "&filename=" + filename + "&number=" + event.target.dataset.num)
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
    let availableWordsArr = [];
    let score = 0;
    let maxScore = 0;
    let questionsNumber = 0;
    let rightAudio = document.getElementById("rightAudio");
    let wrongAudio = document.getElementById("wrongAudio");
    let test = document.createElement("div");
    test.className = "test";
    document.getElementsByTagName("main")[0].append(test);
    let next = document.createElement("i");
    next.className = "next fa-solid fa-right-long";
    test.append(next);
    let currentQuestionNumber = 1;
    for (let i = 0; i < Math.floor(questions.length / 5); i++) {
        questionsNumber++;
        let questionsGroup = document.createElement("div");
        questionsGroup.className = "questions-group";
        test.append(questionsGroup);
        let answersZone = document.createElement("ol");
        answersZone.className = "answers-zone";
        questionsGroup.append(answersZone);
        for (let c = 0; c < (5 > questions.length ? questions.length : 5); c++) {
            let counter = i * 5 + c;
            let question = questions[counter];
            let currentQuestion = document.createElement("li");
            currentQuestion.className = "question";
            answersZone.append(currentQuestion);
            for (let [questionPart, questionAnswers] of Object.entries(question)) {
                currentQuestion.append(questionPart + " ");
                if (questionAnswers.length) {
                    availableWordsArr.push(...questionAnswers);
                    let input = document.createElement("input");
                    input.setAttribute("type", "text");
                    input.dataset.questionNum = counter;
                    input.dataset.questionPart = questionPart;
                    currentQuestion.append(input);
                    maxScore++;
                }
            }
        }
        let availableWords = document.createElement("div");
        availableWords.className = "available-words";
        questionsGroup.prepend(availableWords);
        for (let word of shuffle(availableWordsArr)) {
            let wordCont = document.createElement("span");
            wordCont.className = "word";
            wordCont.append(word);
            availableWords.append(wordCont);
        }
        availableWordsArr = [];
        let submit = document.createElement("button");
        submit.className = "submit";
        submit.textContent = "check answers";
        questionsGroup.append(submit);
        //check answer
        submit.addEventListener("click", function submitAnswers() {
            let foundWrongAnswer = 0;
            for (let finalAnswer of questionsGroup.querySelectorAll("input")) {
                if (finalAnswer.value.toLowerCase().trim() == questions[finalAnswer.dataset.questionNum][finalAnswer.dataset.questionPart][0].toLowerCase().trim()) { //right answer
                    if (finalAnswer.dataset.checked != "true") score++;
                    finalAnswer.style.border = "5px solid green";
                    finalAnswer.setAttribute("disabled", true);
                } else {
                    finalAnswer.style.border = "5px solid red";
                    foundWrongAnswer++;
                }
                finalAnswer.dataset.checked = "true";
            }
            if (foundWrongAnswer) {
                wrongAudio.play();
            } else {
                rightAudio.play();
                submit.addEventListener("click", submitAnswers);
                next.style.width = "auto";
                next.style.height = "auto";
                next.addEventListener("click", function toNextQuestion() {
                    next.removeEventListener("click", toNextQuestion);
                    if (currentQuestionNumber >= questionsNumber) {
                        result();
                        return;
                    }
                    for (let submitBtn of document.getElementsByClassName("submit")) {
                        submitBtn.style.pointerEvents = "none";
                        setTimeout(() => submitBtn.style.pointerEvents = "", 1000)
                    }
                    test.style.marginLeft = test.offsetLeft - document.documentElement.clientWidth + "px";
                    next.style.width = "";
                    next.style.height = "";
                    currentQuestionNumber++;
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
            continueBtn.href = "index.php?grade=" + grade;
            continueBtn.className = "continue-btn";
            continueBtn.textContent = "continue"
            resultPage.append(continueBtn);
        }, 1000);
    }
}
//functions
function shuffle(array) {
    let currentIndex = array.length,  randomIndex;
    while (currentIndex != 0) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;
        [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
    }
    return array;
}
//add date to footer
document.getElementsByClassName("date")[0].innerHTML = 
  document.getElementsByClassName("current-date")[0].innerHTML == new Date().getFullYear() ? 
  "" : " - " + new Date().getFullYear();