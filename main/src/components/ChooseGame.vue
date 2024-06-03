<script setup>
import {computed} from "vue";

const props = defineProps(["answeredQuestions", "answered", "questions", "changeAnswerIsRight", "addRightAnswer", "changeAnswered"]);
const {answeredQuestions, answered, questions, changeAnswerIsRight, addRightAnswer, changeAnswered} = props;
const currentQuestion = computed(() => [questions.value[answeredQuestions.value]]);

function checkAnswer(event) {
    if (event.target.tagName != "BUTTON") return;
    const question = currentQuestion.value[0];
    const rightAnswer = question[1][question[2] - 1];
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
</script>

<template>
    <TransitionGroup>
        <div v-for="question in currentQuestion" :key="question" class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden position-absolute top-0 start-0 question">
            <h2 class="mb-5" data-cy="question">{{ question[0] }}</h2>
            <div @click="$event => checkAnswer($event)" class="row gx-0" data-cy="choices">
                <div 
                    v-for="choice in question[1]" 
                    :key="choice"
                    class="col-sm-6 p-2">
                    <button class="btn w-100 p-2 py-3 h-100" 
                        :data-choice="choice" 
                        :style="{pointerEvents: answered ? 'none' : 'auto'}"
                        :tabIndex="answered ? -1 : 0">{{ choice }}</button>
                </div>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>
button {
    background-color: lightgrey;
}
button:hover {
    background-color: rgb(233, 233, 233);
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