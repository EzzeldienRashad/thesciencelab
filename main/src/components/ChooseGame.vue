<script setup>
import {computed, watch, onMounted} from "vue";

const props = defineProps(["answeredQuestions", "answered", "questions", "changeAnswerIsRight", "addRightAnswer", "changeAnswered"]);
const {answeredQuestions, answered, questions, changeAnswerIsRight, addRightAnswer, changeAnswered} = props;
const currentQuestion = computed(() => [questions.value[answeredQuestions.value]]);

function checkAnswer(event) {
    if (event.target.tagName != "BUTTON") return;
    const rightAnswer = currentQuestion.value[0]["choice" + ["A", "B", "C", "D"][currentQuestion.value[0]["answer"] - 1]];
    if (event.target.dataset.choice == rightAnswer) {
        changeAnswerIsRight("right");
        event.target.style.border = "5px solid green";
        addRightAnswer();
    } else {
        changeAnswerIsRight("wrong");
        event.target.style.border = "5px solid red";
        document.querySelector("button[data-choice='" + rightAnswer + "']").style.border = "5px solid green";
    }
    changeAnswered();
}
const setMainWidth = () => document.getElementsByTagName('MAIN')[0].style.height = document.getElementsByClassName('question')[0].offsetHeight + 50 + 'px';
const unsetMainWidth = () => document.getElementsByTagName('MAIN')[0].style.height = ""; console.log(2)
onMounted(setMainWidth)
</script>

<template>
    <TransitionGroup @after-enter="setMainWidth" @after-leave="unsetMainWidth">
        <div v-for="question in currentQuestion" :key="question['id']" class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden position-absolute top-0 start-0 question">
            <h2 class="mb-5" data-cy="question">{{ question["question"] }}</h2>
            <div class="text-center"><img v-if="question['image']" @load="setMainWidth" :src="'http://127.0.0.1/thesciencelab/info/images/' + question['image']" class="d-inline-block uploaded"/></div>
            <br/>
            <div @click="$event => checkAnswer($event)" class="row gx-0" data-cy="choices">
                <div v-for="i in 4" :key="i" class="col-lg-6 p-2 p-lg-3">
                    <button class="btn w-100 p-2 py-3 h-100 py-sm-4 fs-5 text-dark" 
                        :data-choice="question['choice' + ['A', 'B', 'C', 'D'][i - 1]]" 
                        :style="{pointerEvents: answered ? 'none' : 'auto'}"
                        :tabIndex="answered ? -1 : 0">{{ question['choice' + ['A', 'B', 'C', 'D'][i - 1]] }}</button>
                </div>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>
button {
    background-color: rgba(211, 211, 211, 0.589);
}
button:hover {
    background-color: rgba(233, 233, 233, 0.541);
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