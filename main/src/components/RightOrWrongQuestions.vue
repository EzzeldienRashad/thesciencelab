<script setup>
import {nextTick, computed} from "vue";

const props = defineProps(["rightAnswers", "answeredQuestions", "answered", "questions", "changeAnswerIsRight", "changeRightAnswers", "changeAnswered"]);
const {rightAnswers, answeredQuestions, answered, questions, changeAnswerIsRight, changeRightAnswers, changeAnswered} = props;
const currentQuestion = computed(() => [questions.value[answeredQuestions.value]]);
let rightSound;
let wrongSound;

nextTick(() => {
    rightSound = document.getElementById("rightSound");
    wrongSound = document.getElementById("wrongSound");
});
function checkAnswer(answer) {
    const rightAnswer = Number(currentQuestion.value[0][1]);
    document.querySelector(".answers").querySelectorAll("button")[Number(!rightAnswer)].parentElement.style.border = "5px solid blue";
    document.querySelector(".answers").querySelectorAll("button")[rightAnswer].style.opacity = "0";
    if (answer == rightAnswer) {
        rightSound.play();
        changeRightAnswers(rightAnswers.value + 1);
        document.querySelector("h2").classList.add("text-success");
    } else {
        wrongSound.play();
        document.querySelector("h2").classList.add("text-danger");
    }
    changeAnswered(true);
    Array.from(document.querySelector(".question").querySelectorAll("button")).forEach((el) => {
        el.style.pointerEvents = "none";
        el.tabIndex = -1;
    });
    nextTick(() => document.getElementById("next-arrow").focus());
}
</script>

<template>
    <TransitionGroup>
        <div v-for="[question] in currentQuestion" :key="question" class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden position-absolute top-0 start-0 question">
            <h2 class="mb-5">{{ question }}</h2>
            <div class="answers row p-3 p-md-5">
                <div class="col-6">
                    <button @click="() => !answered && checkAnswer(1)" class="btn w-100 p-2 py-3 border border-danger border-2">
                        <img class="w-100" src="@/assets/icons/right.webp" alt="right">
                    </button>
                </div>
                <div class="col-6">
                    <button @click="() => !answered && checkAnswer(0)" class="btn w-100 p-2 py-3 border border-danger border-2">
                        <img class="w-100" src="@/assets/icons/wrong.webp" alt="wrong">
                    </button>
                </div>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>
button {
    transition: opacity 0.5s ease;
}
button:not(:hover):not(:focus) {
    border: none !important;
}
.answers > div:first-child {
    box-shadow: 2px 0 lightgrey;
}
img {
    max-width: 200px;
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