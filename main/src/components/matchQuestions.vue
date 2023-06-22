<script setup>
import {nextTick} from "vue";

const props = defineProps(["rightAnswers", "answeredQuestions", "answered", "questions", "changeAnswerIsRight", "changeRightAnswers", "changeAnsweredQuestions", "changeAnswered"]);
const {rightAnswers, answeredQuestions, answered, questions, changeAnswerIsRight, changeRightAnswers, changeAnsweredQuestions, changeAnswered} = props;
let checked = false;
const shuffledQuestionGroups = shuffle(questions.value);
const shuffledQuestions = [];
for (let questionGroup of shuffledQuestionGroups) {
    const shuffledKeys = shuffle(Object.keys(questionGroup));
    const shuffledValues = shuffle(Object.values(questionGroup));
    shuffledQuestions.push({"questions": shuffledKeys, "answers": shuffledValues});
}
let rightSound;
let wrongSound;

nextTick(() => {
    rightSound = document.getElementById("rightSound");
    wrongSound = document.getElementById("wrongSound");
});

function checkAnswer(n) {
    const questionTexts = document.querySelector(".answers").getElementsByClassName("question-text");
    let foundWrongAnswer = false;
    for (let questionText of questionTexts) {
        if (shuffledQuestionGroups[n][questionText.dataset.question] === undefined) continue;
        if (!checked) changeAnsweredQuestions(answeredQuestions.value + 1);
        if (shuffledQuestionGroups[n][questionText.dataset.question] == questionText.dataset.answer) {
            shuffledQuestionGroups[n][questionText.dataset.question] = undefined;
            questionText.style.color = "green";
            questionText.parentElement.querySelector(".connect-line").classList.remove("bg-primary");
            questionText.parentElement.querySelector(".connect-line").classList.remove("bg-danger");
            questionText.parentElement.querySelector(".connect-line").classList.add("bg-success");
            questionText.parentElement.querySelector(".circle-end").dataset.disabled = true;
            if (!checked) changeRightAnswers(rightAnswers.value + 1);
        } else if (shuffledQuestionGroups[n][questionText.dataset.question] != questionText.dataset.answer) {
            if (!checked) {
                questionText.style.color = "red";
                questionText.parentElement.querySelector(".connect-line").classList.remove("bg-primary");
                questionText.parentElement.querySelector(".connect-line").classList.add("bg-danger");
            }
            foundWrongAnswer = true;
        }
    }
    checked = true;
    if (foundWrongAnswer) {
        wrongSound.play();
    } else {
        rightSound.play();
        changeAnswerIsRight("right");
        setTimeout(() => changeAnswerIsRight(""), 750);
        if (!answered.value) changeAnswered(true);
        checked = false;
    }
}
function moveCircle(event) {
    event.preventDefault();
    const circle = event.currentTarget;
    if (circle.dataset.disabled == "true") return;
    const startCircle = circle.parentElement.querySelector(".circle-start");
    const connectLine = startCircle.querySelector(".connect-line");
    let currentDroppable = null;
    const shiftX = event.clientX - circle.getBoundingClientRect().left;
    const shiftY = event.clientY - circle.getBoundingClientRect().top;
    addEventListener("mousemove", moveToMouse);
    addEventListener("mouseup", leaveMouse);
    function positionCircle(x, y, shiftX = 0, shiftY = 0) {
        if (x < document.querySelector("tbody").getBoundingClientRect().right - circle.offsetWidth / 2 &&
            x > document.querySelector("tbody").getBoundingClientRect().left) {
            circle.style.left = x - startCircle.getBoundingClientRect().left - shiftX + "px";
        }
        if (y < document.querySelector("tbody").getBoundingClientRect().bottom - circle.offsetHeight / 2 &&
            y > document.querySelector("tbody").getBoundingClientRect().top) {
            circle.style.top = y - startCircle.getBoundingClientRect().top - shiftY + "px";
        }
        // pythagorean theorem
        const sideX = circle.getBoundingClientRect().left - startCircle.getBoundingClientRect().left;
        const sideY = circle.getBoundingClientRect().top - startCircle.getBoundingClientRect().top;
        const sideZ = Math.sqrt(sideX ** 2 + sideY ** 2);
        let rotation = Math.asin(sideY / sideZ)  * 180 / Math.PI; 
        if (sideX < 0) rotation = 180 - rotation;
        connectLine.style.width = sideZ + "px";
        connectLine.style.transform = "rotate(" + rotation + "deg)";

    }
    function moveToMouse(moveEvent) {
        moveEvent.preventDefault();
        circle.classList.remove("d-flex");
        circle.classList.add("d-none");
        const droppableBelow = document.elementFromPoint(moveEvent.clientX, moveEvent.clientY)?.closest("td.droppable");
        circle.classList.remove("d-none");
        circle.classList.add("d-flex");
        if (droppableBelow && currentDroppable != droppableBelow) {
            if (currentDroppable) currentDroppable.classList.remove("answer-active");
            currentDroppable = droppableBelow;
            droppableBelow.classList.add("answer-active");
        } else if (!droppableBelow && currentDroppable) {
            currentDroppable.classList.remove("answer-active");
            currentDroppable = null;
        }
        positionCircle(moveEvent.clientX, moveEvent.clientY, shiftX, shiftY);
    }
    function leaveMouse() {
        if (currentDroppable) {
            currentDroppable.classList.remove("answer-active");
            const pin = currentDroppable.querySelector(".pin");
            positionCircle(pin.getBoundingClientRect().left - rem2px(0.25), pin.getBoundingClientRect().top - rem2px(0.25));
            circle.closest("td").querySelector(".question-text").dataset.answer = currentDroppable.closest("td").querySelector(".answer-text").dataset.text;
        } else {
            circle.style.top = "";
            circle.style.left = "";
            connectLine.style.width = "";
            connectLine.style.transform = "";
            circle.closest("td").querySelector(".question-text").dataset.answer = "";
        }
        removeEventListener("mousemove", moveToMouse);
        removeEventListener("mouseup", leaveMouse);
    }
}
function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}
function rem2px(rem) {    
    return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}
</script>

<template>
    <div class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden" v-for="n in shuffledQuestions.length" :key="n">
        <div class="question pb-3 table-responsive">
            <table class="table table-bordered">
                <tbody class="answers">
                    <tr>
                        <th class="text-center">Column A</th>
                        <th class="text-center">Column B</th>
                    </tr>
                    <tr v-for="c in shuffledQuestions[n - 1]['questions'].length" :key="c">
                        <td class="py-3">
                            <div class="d-flex">
                                <div :data-question="shuffledQuestions[n - 1]['questions'][c - 1]" data-answer="" class="question-text flex-grow-1 border-end border-2 pe-2">
                                    {{ shuffledQuestions[n - 1]['questions'][c - 1] }}
                                </div>
                                <div class="circles-cont pe-5 ps-1s">
                                    <div>
                                        <div class="circle-outer circle-start d-flex align-items-center justify-content-center bg-warning rounded-circle position-absolute top-0 start-0 pointer-event pe-none">
                                            <div class="circle-inner bg-primary rounded-circle">
                                                <div class="connect-line bg-primary position-absolute start-50"></div>
                                            </div>
                                        </div>
                                        <div class="circle-outer circle-end d-flex align-items-center justify-content-center bg-warning rounded-circle position-absolute"
                                            @mousedown="$event => moveCircle($event)"
                                            data-disabled="false">
                                            <div class="circle-inner bg-primary rounded-circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="droppable py-3">
                            <div class="d-flex align-items-center">
                                <div class="ps-4 pe-1 border-end border-2 me-2">
                                    <div class="pin bg-dark rounded-circle pe-none"></div>
                                </div>
                                <div :data-text="shuffledQuestions[n - 1]['answers'][c - 1]" class="answer-text flex-grow-1">
                                    {{ shuffledQuestions[n - 1]['answers'][c - 1] }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button @click="!answered && checkAnswer(n - 1)" :disabled="answered" class="w-100 p-2 h-3 text-bg-primary rounded-2 fw-bold" :class="{'opacity-dec': answered}">done</button>
    </div>
</template>

<style scoped>
button {
    transition: opacity 0.5s ease;
}
img {
    max-width: 200px;
}
.opacity-dec {
    opacity: 0.7;
}
td {
    min-width: 17rem;
    vertical-align: middle;
}
.circles-cont {
    margin-top: 2px;
    z-index: 2;
}
.circle-outer {
    width: 1.3rem;
    height: 1.3rem;
    cursor: pointer;
}
.circle-inner {
    width: 0.8rem;
    height: 0.8rem
}
.circle-end {
    z-index: 3;
}
.pin {
    width: 0.8rem;
    height: 0.8rem;
}
.connect-line {
    height: 0.5rem;
    z-index: -1;
    transform-origin: center left;
    top: 0.15rem;
}
.answer-active {
    background-color: lightskyblue;
}
</style>