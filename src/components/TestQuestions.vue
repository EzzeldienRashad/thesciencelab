<script setup>
import {inject, ref, defineEmits} from "vue";
import {useRoute} from "vue-router";
import ChooseQuestions from "@/components/ChooseQuestions.vue";
import RightOrWrongQuestions from "@/components/RightOrWrongQuestions.vue";
import CompleteQuestions from "@/components/CompleteQuestions.vue";

const routeParams = useRoute().params;
const emit = defineEmits(["result"]);
const questions = inject("questions");
const answerIsRight = ref("");
const answered = ref(false);
const transitioning = ref(false);
const questionWidth = ref(document.documentElement.offsetWidth);
const questionsCont = ref(null);
const transitionDuration = "1s";
const rightAnswers = ref(0);
const answeredQuestions = ref(0);
const inheritedVariables = {
    rightAnswers: rightAnswers, 
    answeredQuestions: answeredQuestions,
    answered: answered, 
    questions: questions,
    changeRightAnswers(value) {rightAnswers.value = value},
    changeAnsweredQuestions(value) {answeredQuestions.value = value},
    changeAnswered(value) {answered.value = value}
};

function changeAnswerIsRight(value) {answerIsRight.value = value}
function next() {
    if (answeredQuestions.value >= Object.keys(questions.value).length) {
        emit("result", routeParams.game, rightAnswers.value, answeredQuestions.value);
        return;
    }
    transitioning.value = true;
    questionWidth.value = questionsCont.value.firstElementChild.offsetWidth;
    questionsCont.value.style.marginLeft = questionsCont.value.offsetLeft - questionWidth.value + "px";
    setTimeout(() => {
        transitioning.value = false;
        answered.value = false;
        const answers = document.querySelector(".answers");
        answers.parentElement.style.height = answers.parentElement.offsetHeight + "px";
        answers.remove();
    }, parseFloat(transitionDuration) * 1000);
}
</script>

<template>
    <div>
        <div ref="questionsCont" id="questions-cont" class="d-flex overflow-hidden">
            <ChooseQuestions v-bind="inheritedVariables" :changeAnswerIsRight="changeAnswerIsRight" v-if="routeParams.game == 'choose'" />
            <RightOrWrongQuestions v-bind="inheritedVariables" v-if="routeParams.game == 'right_or_wrong'" />
            <CompleteQuestions v-if="routeParams.game == 'complete'" />
        </div>
        <img v-if="answerIsRight == 'right'" src="@/assets/icons/right.webp" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio id="rightSound" src="/src/assets/audio/right.mp3"></audio>
        <img v-if="answerIsRight == 'wrong'" src="@/assets/icons/wrong.webp" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio id="wrongSound" src="/src/assets/audio/wrong.mp3"></audio>
        <font-awesome-icon v-if="answered && !transitioning" @click="!transitioning && answered && next()" id="next-arrow" icon="fa-solid fa-right-long" size="3x" role="button" class="position-fixed text-primary top-50 translate-middle-y"/>
    </div>
</template>

<style scoped>
div#questions-cont {
    width: max-content;
    transition: margin-left v-bind(transitionDuration) ease;
}
img {
    bottom: 50px;
}
#next-arrow {
    right: 20px;
}
#next-arrow:hover {
    filter: drop-shadow(-3px 3px black);
}
</style>