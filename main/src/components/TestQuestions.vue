<script setup>
import {inject, nextTick, ref, watch} from "vue";
import {useRoute} from "vue-router";
import ChooseQuestions from "@/components/ChooseQuestions.vue";
import CompleteQuestions from "@/components/CompleteQuestions.vue";
import RightOrWrongQuestions from "@/components/RightOrWrongQuestions.vue";
import matchQuestions from "@/components/matchQuestions.vue";
import rightSound from "@/assets/audio/right.mp3";
import wrongSound from "@/assets/audio/wrong.mp3";
import rightImg from "@/assets/icons/right.webp";
import wrongImg from "@/assets/icons/wrong.webp";

const routeParams = useRoute().params;
const emit = defineEmits(["result"]);
const questions = inject("questions");
const theme = inject("theme");
let questionsLength = Object.keys(questions.value).length;
if (routeParams.game == "complete") {
    questionsLength = questions.value.reduce(function (sum, obj) {
        let questionsNum = 0;
        for (let value of Object.values(obj)) {
            if (value.length) questionsNum++;
        }
        return sum + questionsNum
    }, 0);
} else if (routeParams.game == "match") {
    questionsLength = questions.value.reduce((sum, obj) => sum + Object.keys(obj).length, 0);
}
const answerIsRight = ref("");
const answered = ref(false);
const transitioning = ref(false);
const documentWidth = inject("documentWidth");
const questionPagesAnswered = ref(0);
const questionsCont = ref(null);
const transitionDuration = ref("1s");
const rightAnswers = ref(0);
const answeredQuestions = ref(0);
const inheritedVariables = {
    rightAnswers, 
    answeredQuestions,
    answered, 
    questions,
    changeAnswerIsRight(value) {answerIsRight.value = value},
    changeRightAnswers(value) {rightAnswers.value = value},
    changeAnsweredQuestions(value) {answeredQuestions.value = value},
    changeAnswered(value) {answered.value = value}
};

watch(() => -questionPagesAnswered.value * documentWidth.value + "px", newMarginLeft => {
    if (!transitioning.value) {
        transitionDuration.value = "0s";
        questionsCont.value.style.marginLeft = newMarginLeft;
        setTimeout(() => {
            transitionDuration.value = "1s";
        });
    } else {
        questionsCont.value.style.marginLeft = newMarginLeft;
    }
});

function next() {
    if (answeredQuestions.value >= questionsLength) {
        emit("result", routeParams.game, rightAnswers.value, answeredQuestions.value);
        return;
    } else {
        questionPagesAnswered.value++;
    }
    transitioning.value = true;
    setTimeout(() => {
        transitioning.value = false;
        answered.value = false;
        const question = document.querySelector(".question");
        question.parentElement.style.height = question.parentElement.offsetHeight + "px";
        const nextQuestion = question.parentElement.nextElementSibling;
        nextQuestion.querySelectorAll("button").forEach((btn) => btn.tabIndex = 0);
        nextQuestion.querySelectorAll("button").forEach((btn) => btn.style.pointerEvents = "all");
        nextQuestion.querySelectorAll("input").forEach((btn) => btn.style.pointerEvents = "all");
        nextQuestion.querySelectorAll("input").forEach((btn) => btn.tabIndex = 0);
        nextTick(() => (nextQuestion.querySelector("input") || nextQuestion.querySelector("button")).focus());
        question.remove();
    }, parseFloat(transitionDuration.value) * 1000);
}
</script>

<template>
    <div>
        <div class="progress">
            <div class="progress-bar" :style="{width: answeredQuestions * 100 / questionsLength + '%'}"></div>
        </div>
        <div ref="questionsCont" id="questions-cont" class="d-flex overflow-hidden" :style="{transition: 'margin-left ' + transitionDuration + ' ease'}">
            <ChooseQuestions v-bind="inheritedVariables" v-if="routeParams.game == 'choose'" />
            <RightOrWrongQuestions v-bind="inheritedVariables" v-else-if="routeParams.game == 'right-or-wrong'" />
            <CompleteQuestions v-bind="inheritedVariables" v-else-if="routeParams.game == 'complete'" />
            <matchQuestions v-bind="inheritedVariables" v-else-if="routeParams.game == 'match'" />
        </div>
        <img v-if="answerIsRight == 'right'" :src="rightImg" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio id="rightSound" :src="rightSound"></audio>
        <img v-if="answerIsRight == 'wrong'" :src="wrongImg" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio id="wrongSound" :src="wrongSound"></audio>
        <button 
        id="next-arrow" 
        class="position-fixed top-50 translate-middle-y border-0 p-0 bg-transparent"
        v-if="answered && !transitioning" 
        @click="!transitioning && answered && next()">
            <font-awesome-icon 
                id="next-arrow-symbol"
                icon="fa-solid fa-right-long" 
                size="4x"  
                :class="'text-' + theme"/>
        </button>
    </div>
</template>

<style scoped>
div#questions-cont {
    width: max-content;
}
img {
    bottom: 50px;
}
#next-arrow {
    right: 20px;
}
#next-arrow:focus {
    outline: none;
}
#next-arrow:focus > #next-arrow-symbol {
    filter: drop-shadow(-2px 2px black);
}
#next-arrow > #next-arrow-symbol:hover {
    filter: drop-shadow(-3px 3px 2px black);
}
.progress {
    border-radius: 0 !important;
    height: 12px;
}
</style>