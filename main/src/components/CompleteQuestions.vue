<script setup>
import {ref, nextTick} from "vue";

const props = defineProps(["rightAnswers", "answeredQuestions", "answered", "questions", "changeAnswerIsRight", "changeRightAnswers", "changeAnsweredQuestions", "changeAnswered"]);
const {rightAnswers, answeredQuestions, answered, questions, changeAnswerIsRight, changeRightAnswers, changeAnsweredQuestions, changeAnswered} = props;
const shuffledQuestions = shuffle(questions.value);
let checked = false;
let questionGroups = [];
for (let i = 0; i < shuffledQuestions.length; i += 5) {
    questionGroups.push(shuffledQuestions.slice(i, i + 5));
}
const choices = [];
const questionsAnswers = [];
for (let questionGroup of questionGroups) {
    let choicesArr = [];
    for (let question of questionGroup) {
        for (let answers of Object.values(question)) {
            if (answers.length) {
                choicesArr.push(...answers);
                questionsAnswers.push(answers[0]);
            }
        }
    }    
    choices.push(shuffle(choicesArr));
}
let rightSound;
let wrongSound;

nextTick(() => {
    rightSound = document.getElementById("rightSound");
    wrongSound = document.getElementById("wrongSound");
});

function checkAnswer() {
    const inputs = document.getElementsByClassName("answers")[0].getElementsByTagName("input");
    let foundWrongAnswer = false;
    for (let i = 0; i < inputs.length; i++) {
        if (!checked) changeAnsweredQuestions(answeredQuestions.value + 1);
        if (questionsAnswers[i] !== false && inputs[i].value.trim().toLowerCase() == questionsAnswers[i].trim().toLowerCase()) {
            inputs[i].style.color = "green";
            inputs[i].style.borderBottom = "2px dotted green";
            inputs[i].disabled = true;
            questionsAnswers[i] = false;
            if (!checked) changeRightAnswers(rightAnswers.value + 1);
        } else if (questionsAnswers[i] !== false && inputs[i].value.trim() != questionsAnswers[i].trim()) {
            if (!checked) {
                inputs[i].style.borderBottom = "2px solid red";
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
        for (let input of inputs) {
            questionsAnswers.shift();
        }
    }
}
function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}
</script>

<template>
    <div class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden" v-for="n in questionGroups.length" :key="n">
        <div class="question">
            <div class="text-bg-info rounded-1 p-1 p-md-5">
                {{ choices[n - 1][0] }}
                <template v-for="choice in choices[n - 1].slice(1)" :key="choice">
                    &nbsp;&nbsp;,&nbsp;&nbsp; {{ choice }} 
                </template>
            </div>
            <ol class="answers border border-2 border-dark rounded-2 mt-3 py-2">
                <li v-for="question in questionGroups[n - 1]" :key="question" class="pb-3">
                    <template v-for="(answers, statement) in question" :key="statement">
                        <template v-if="statement.trim()">{{ statement }}</template>
                        <input v-if="answers.length" :disabled="answered" size="10" type="text" class="bg-light border-top-0 border-start-0 border-end-0 answer-input"/>
                    </template>
                </li>
            </ol>
            <button @click="!answered && checkAnswer()" :disabled="answered" class="w-100 p-2 h-3 text-bg-primary rounded-2 fw-bold" :class="{'opacity-dec': answered}">check</button>
        </div>
    </div>
</template>

<style scoped>
button {
    transition: opacity 0.5s ease;
}
img {
    max-width: 200px;
}
.answer-input {
    border-bottom: 2px dotted grey;
    border-bottom-style: dotted;
    outline: none;
}
.opacity-dec {
    opacity: 0.7
}
</style>