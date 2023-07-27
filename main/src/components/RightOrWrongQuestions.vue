<script setup>
import {ref, nextTick, inject} from "vue";

const props = defineProps(["rightAnswers", "answeredQuestions", "answered", "questions", "changeAnswerIsRight", "changeRightAnswers", "changeAnsweredQuestions", "changeAnswered"]);
const {rightAnswers, answeredQuestions, answered, questions, changeAnswerIsRight, changeRightAnswers, changeAnsweredQuestions, changeAnswered} = props;
const rightBtns = ref([]);
const wrongBtns = ref([]);
const Btns = [wrongBtns, rightBtns];
let rightSound;
let wrongSound;
const theme = inject("theme");
const themeColor = theme.value == "success" ? "green" : theme.value == "warning" ? yellow : theme.value == "danger" ? "red" : "blue";

nextTick(() => {
    rightSound = document.getElementById("rightSound");
    wrongSound = document.getElementById("wrongSound");
});
//remove the right and wrong buttons from ref on checkanswer
function checkAnswer(givenAnswer, rightAnswer) {
    Btns[parseInt(rightAnswer)].value[answeredQuestions.value].parentElement.style.border = "5px solid blue";
    Btns[Number(!parseInt(rightAnswer))].value[answeredQuestions.value].style.opacity = "0";
    if (givenAnswer == rightAnswer) {
        rightSound.play();
        changeRightAnswers(rightAnswers.value + 1);
        document.querySelector("h2").classList.add("text-success");
    } else {
        wrongSound.play();
        document.querySelector("h2").classList.add("text-danger");
    }
    changeAnsweredQuestions(answeredQuestions.value + 1);
    changeAnswered(true);
    Array.from(document.querySelector(".question").querySelectorAll("button")).forEach((el) => {
        el.style.pointerEvents = "none";
        el.tabIndex = -1;
    });
    nextTick(() => document.getElementById("next-arrow").focus());
}
</script>

<template>
    <div class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden" v-for="(answer, question, index) in questions" :key="question">
        <div class="question">
            <h2 class="mb-5">{{ question }}</h2>
            <div class="answers row p-3 p-md-5">
                <div
                    class="col-6">
                    <button :tabindex="index ? -1 : 0" :style="{pointerEvents: index ? 'none' : 'all'}" ref="rightBtns" @click="() => !answered && checkAnswer(1, answer)" class="btn w-100 p-2 py-3 border border-danger border-2">
                        <img class="w-100" src="@/assets/icons/right.webp" alt="right">
                    </button>
                </div>
                <div
                class="col-6">
                    <button :tabindex="index ? -1 : 0" :style="{pointerEvents: index ? 'none' : 'all'}" ref="wrongBtns" @click="() => !answered && checkAnswer(0, answer)" class="btn w-100 p-2 py-3 border border-danger border-2">
                        <img class="w-100" src="@/assets/icons/wrong.webp" alt="wrong">
                    </button>
                </div>
            </div>
        </div>
    </div>
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
</style>