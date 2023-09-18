<script setup>
import {computed} from "vue";
import rightImg from "@/assets/icons/right.webp";
import wrongImg from "@/assets/icons/wrong.webp";

const props = defineProps(["answeredQuestions", "answered", "questions", "changeAnswerIsRight", "addRightAnswer", "changeAnswered"]);
const {answeredQuestions, answered, questions, changeAnswerIsRight, addRightAnswer, changeAnswered} = props;
const currentQuestion = computed(() => [questions.value[answeredQuestions.value]]);

function checkAnswer(answer) {
    const rightAnswer = Number(currentQuestion.value[0][1]);
    document.querySelector(".answers").querySelectorAll("button")[Number(!rightAnswer)].parentElement.style.border = "5px solid blue";
    document.querySelector(".answers").querySelectorAll("button")[rightAnswer].style.opacity = "0";
    if (answer == rightAnswer) {
        changeAnswerIsRight("right");
        addRightAnswer();
        document.querySelector("h2").classList.add("text-success");
    } else {
        changeAnswerIsRight("wrong");
        document.querySelector("h2").classList.add("text-danger");
    }
    changeAnswered();

}
</script>

<template>
    <TransitionGroup>
        <div v-for="[question] in currentQuestion" :key="question" class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden position-absolute top-0 start-0 question">
            <h2 class="mb-5" data-cy="question">{{ question }}</h2>
            <div class="answers row p-3 p-md-5" data-cy="choices">
                <div class="col-6">
                    <button @click="() => !answered && checkAnswer(1)" 
                        class="btn w-100 p-2 py-3 border border-danger border-2"
                        :style="{pointerEvents: answered ? 'none' : 'auto'}"
                        :tabIndex="answered ? -1 : 0">
                        >
                        <img class="w-100" :src="rightImg" alt="right">
                    </button>
                </div>
                <div class="col-6">
                    <button @click="() => !answered && checkAnswer(0)" 
                        class="btn w-100 p-2 py-3 border border-danger border-2"
                        :style="{pointerEvents: answered ? 'none' : 'auto'}"
                        :tabIndex="answered ? -1 : 0">
                        <img class="w-100" :src="wrongImg" alt="wrong">
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