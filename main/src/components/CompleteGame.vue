<script setup>
import {computed} from "vue";

const props = defineProps(["answeredQuestions", "answered", "questions", "changeAnswerIsRight", "addRightAnswer", "changeAnswered"]);
const {answeredQuestions, answered, questions, changeAnswerIsRight, addRightAnswer, changeAnswered} = props;
let checked = false;
const currentQuestionGroupArr = computed(() => [questions.value[answeredQuestions.value]]);
const choices = [];
for (let questionGroup of questions.value) {
    const choicesArr = [];
    for (let question of questionGroup) {
        choicesArr.push(question["rightAnswer"], question["wrongAnswer"]);
    }    
    choices.push(shuffle(choicesArr));
}
const currentChoices = computed(() => choices[answeredQuestions.value]);

function checkAnswer() {
    const inputs = document.querySelector(".answers").querySelectorAll("input");
    let foundWrongAnswer = false;
    for (let i = 0; i < inputs.length; i++) {
        if (currentQuestionGroupArr.value[0][i]["rightAnswer"] && currentQuestionGroupArr.value[0][i]["wrongAnswer"] && inputs[i].value.trim().toLowerCase() == currentQuestionGroupArr.value[0][i]["rightAnswer"].trim().toLowerCase()) {
            inputs[i].style.color = "green";
            inputs[i].style.borderBottom = "2px dotted green";
            inputs[i].disabled = true;
            currentQuestionGroupArr.value[0][i][1] = [];
            if (!checked) addRightAnswer();
        } else if (currentQuestionGroupArr.value[0][i]["rightAnswer"] && currentQuestionGroupArr.value[0][i]["wrongAnswer"] && inputs[i].value.trim().toLowerCase() != currentQuestionGroupArr.value[0][i]["rightAnswer"].trim().toLowerCase()) {
            if (!checked) {
                inputs[i].style.borderBottom = "2px solid red";
            }
            foundWrongAnswer = true;
        }
    }
    checked = true;
    if (foundWrongAnswer) {
        changeAnswerIsRight("wrong");
        document.querySelector("input:not([disabled])").focus();
    } else {
        changeAnswerIsRight("right");
        if (!answered.value) changeAnswered();
        checked = false;
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
    <TransitionGroup>
        <div v-for="currentQuestionGroup in currentQuestionGroupArr" :key="currentQuestionGroup" class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden position-absolute top-0 start-0 question">
            <div class="text-bg-info rounded-1 p-1 p-md-5" data-cy="choices">
                {{ currentChoices[0] }}
                <template v-for="choice in currentChoices.slice(1)" :key="choice">
                    &nbsp;&nbsp;,&nbsp;&nbsp; {{ choice }} 
                </template>
            </div>
            <ol class="answers border border-2 border-dark rounded-2 mt-3 py-2" data-cy="questions">
                <li v-for="question in currentQuestionGroup" :key="question['id']" class="pb-3">
                    {{ question["part1"] }}
                    <input :disabled="answered" size="10" type="text" class="bg-light border-top-0 border-start-0 border-end-0 answer-input"/>
                    {{ question["part2"] }}
                </li>
            </ol>
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
.answer-input {
    border-bottom: 2px dotted grey;
    border-bottom-style: dotted;
    outline: none;
}
.opacity-dec {
    opacity: 0.7
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