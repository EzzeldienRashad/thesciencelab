<script setup>
import {computed, watch} from "vue";

const props = defineProps(["answeredQuestions", "answered", "questions", "changeAnswerIsRight", "addRightAnswer", "changeAnswered"]);
const {answeredQuestions, answered, questions, changeAnswerIsRight, addRightAnswer, changeAnswered} = props;
let checked = false;
let currentQuestion = {};
for (let i = 0; i < JSON.parse(questions.value[answeredQuestions.value]["colA"]).length; i++) {
    currentQuestion[JSON.parse(questions.value[answeredQuestions.value]["colA"])[i]] = JSON.parse(questions.value[answeredQuestions.value]["colB"])[i];
}
const shuffledQuestions = [];
for (let questionGroup of questions.value) {
    const shuffledKeys = shuffle(JSON.parse(questionGroup["colA"]));
    const shuffledValues = shuffle(JSON.parse(questionGroup["colB"]));
    shuffledQuestions.push({"questions": shuffledKeys, "answers": shuffledValues});
}
const currentShuffledQuestion = computed(() => [shuffledQuestions[answeredQuestions.value]]);

watch(answeredQuestions, (answeredQuestionsValue) => {
    currentQuestion = {};
    for (let i = 0; i < JSON.parse(questions.value[answeredQuestions.value]["colA"]).length; i++) {
        currentQuestion[JSON.parse(questions.value[answeredQuestions.value]["colA"])[i]] = JSON.parse(questions.value[answeredQuestions.value]["colB"])[i];
    }
});

function checkAnswer() {
    const questionTexts = document.querySelector(".answers").getElementsByClassName("question-text");
    let foundWrongAnswer = false;
    for (let questionText of questionTexts) {
        if (!currentQuestion[questionText.dataset.question]) continue;
        if (currentQuestion[questionText.dataset.question] == questionText.dataset.answer) {
            delete currentQuestion[questionText.dataset.question];
            questionText.style.color = "green";
            questionText.parentElement.querySelector(".connect-line").classList.remove("bg-primary");
            questionText.parentElement.querySelector(".connect-line").classList.remove("bg-danger");
            questionText.parentElement.querySelector(".connect-line").classList.add("bg-success");
            questionText.parentElement.querySelector(".circle-end").dataset.disabled = true;
            if (!checked) addRightAnswer();
        } else if (currentQuestion[questionText.dataset.question] != questionText.dataset.answer) {
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
        changeAnswerIsRight("wrong");
    } else {
        changeAnswerIsRight("right");
        if (!answered.value) changeAnswered();
        checked = false;
    }
}
function moveCircle(event) {
    const circle = event.currentTarget;
    circle.classList.remove("d-flex");
    circle.classList.add("d-none");
    if (document.elementFromPoint(event.clientX, event.clientY)?.closest("td.droppable")) document.elementFromPoint(event.clientX, event.clientY)?.closest("td.droppable").classList.remove("busy");
    circle.classList.remove("d-none");
    circle.classList.add("d-flex");
    if (circle.dataset.disabled == "true") return;
    const startCircle = circle.parentElement.querySelector(".circle-start");
    const connectLine = startCircle.querySelector(".connect-line");
    let currentDroppable = null;
    const shiftX = event.clientX - circle.getBoundingClientRect().left;
    const shiftY = event.clientY - circle.getBoundingClientRect().top;
    addEventListener("pointermove", moveTopointer);
    addEventListener("pointerup", leavepointer);
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
    function moveTopointer(moveEvent) {
        moveEvent.preventDefault();
        circle.classList.remove("d-flex");
        circle.classList.add("d-none");
        const droppableBelow = document.elementFromPoint(moveEvent.clientX, moveEvent.clientY)?.closest("td.droppable");
        const droppableBelowAvailable = droppableBelow && !droppableBelow.classList.contains("busy");
        circle.classList.remove("d-none");
        circle.classList.add("d-flex");
        if (droppableBelow && currentDroppable != droppableBelow) {
            if (currentDroppable) currentDroppable.classList.remove("answer-active");
            if (droppableBelowAvailable) {
                currentDroppable = droppableBelow;
                droppableBelow.classList.add("answer-active");
            }
        } else if (!droppableBelow && currentDroppable) {
            currentDroppable.classList.remove("busy");
            currentDroppable.classList.remove("answer-active");
            currentDroppable = null;
        }
        positionCircle(moveEvent.clientX, moveEvent.clientY, shiftX, shiftY);
    }
    function leavepointer() {
        if (currentDroppable && !currentDroppable.classList.contains("busy")) {
            currentDroppable.classList.remove("answer-active");
            const pin = currentDroppable.querySelector(".pin");
            positionCircle(pin.getBoundingClientRect().left - rem2px(0.25), pin.getBoundingClientRect().top - rem2px(0.25));
            circle.closest("td").querySelector(".question-text").dataset.answer = currentDroppable.closest("td").querySelector(".answer-text").dataset.text;
            currentDroppable.classList.add("busy");
        } else {
            circle.style.top = "";
            circle.style.left = "";
            connectLine.style.width = "";
            connectLine.style.transform = "";
            circle.closest("td").querySelector(".question-text").dataset.answer = "";
        }
        removeEventListener("pointermove", moveTopointer);
        removeEventListener("pointerup", leavepointer);
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
    <TransitionGroup>
        <div v-for="shuffledQuestion in currentShuffledQuestion" :key="shuffledQuestion" class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden position-absolute top-0 start-0">
            <div class="pb-3 table-responsive">
                <table class="table table-bordered">
                    <tbody class="answers">
                        <tr>
                            <th class="text-center">Column A</th>
                            <th class="text-center">Column B</th>
                        </tr>
                        <tr v-for="c in shuffledQuestion['questions'].length" :key="c">
                            <td class="py-3">
                                <div class="d-flex">
                                    <div :data-question="shuffledQuestion['questions'][c - 1]" data-answer="" class="question-text flex-grow-1 border-end border-2 pe-2">
                                        {{ shuffledQuestion['questions'][c - 1] }}
                                    </div>
                                    <div class="circles-cont pe-5 ps-1s">
                                        <div>
                                            <div class="circle-outer circle-start d-flex align-items-center justify-content-center bg-warning rounded-circle position-absolute top-0 start-0 pointer-event pe-none">
                                                <div class="circle-inner bg-primary rounded-circle">
                                                    <div class="connect-line bg-primary position-absolute start-50"></div>
                                                </div>
                                            </div>
                                            <div class="circle-outer circle-end d-flex align-items-center justify-content-center bg-warning rounded-circle position-absolute"
                                                @pointerdown.prevent="$event => moveCircle($event)"
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
                                    <div :data-text="shuffledQuestion['answers'][c - 1]" class="answer-text flex-grow-1">
                                        {{ shuffledQuestion['answers'][c - 1] }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button @click="!answered && checkAnswer()" :disabled="answered" class="w-100 p-2 h-3 text-bg-primary rounded-2 fw-bold" :class="{'opacity-dec': answered}">check</button>
        </div>
    </TransitionGroup>
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
    touch-action: none;
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
.v-move, .v-enter-active, .v-leave-active {
    transition: transform 0.5s ease;
}
.v-enter-from {
    transform: translateX(100%);
}
 .v-leave-to {
    transform: translateX(-100%);
}
</style>