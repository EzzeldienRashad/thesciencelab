<script setup>
import {nextTick} from "vue";

const props = defineProps(["rightAnswers", "answeredQuestions", "answered", "questions", "changeAnswerIsRight", "changeRightAnswers", "changeAnsweredQuestions", "changeAnswered"]);
const {rightAnswers, answeredQuestions, answered, questions, changeAnswerIsRight, changeRightAnswers, changeAnsweredQuestions, changeAnswered} = props;
let rightSound;
let wrongSound;
nextTick(() => {
    rightSound = document.getElementById("rightSound");
    wrongSound = document.getElementById("wrongSound");
});

function checkAnswer(event, rightAnswer) {
    if (event.target.tagName != "BUTTON") return;
    if (event.target.dataset.choice == JSON.parse(event.target.parentElement.parentElement.dataset.choices)[rightAnswer - 1]) {
        changeAnswerIsRight("right");
        rightSound.play();
        event.target.style.border = "5px solid green";
        setTimeout(() => changeAnswerIsRight(""), 750);
        changeRightAnswers(rightAnswers.value + 1);
    } else {
        changeAnswerIsRight("wrong");
        wrongSound.play();
        event.target.style.border = "5px solid red";
        document.querySelector("button[data-choice='" + JSON.parse(event.target.parentElement.parentElement.dataset.choices)[rightAnswer - 1] + "']").style.border = "5px solid green";
        setTimeout(() => changeAnswerIsRight(""), 750);
    }
    changeAnsweredQuestions(answeredQuestions.value + 1);
    changeAnswered(true);
    Array.from(event.target.parentElement.parentElement.querySelectorAll("button")).forEach((el) => el.style.pointerEvents = "none");
}
</script>

<template>
    <div class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden" v-for="question in questions" :key="question[0]">
        <div class="question">
            <h2 class="mb-5">{{ question[0] }}</h2>
            <div :data-choices="JSON.stringify(question[1])" @click="$event => !answered && checkAnswer($event, question[2])" class="row gx-0">
                <div 
                    v-for="choice in question[1]" 
                    :key="choice"
                    class="col-sm-6 p-2">
                    <button class="btn w-100 p-2 py-3" :data-choice="choice">{{ choice }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
button {
    background-color: lightgrey;
}
button:hover {
    background-color: rgb(233, 233, 233);
}
</style>