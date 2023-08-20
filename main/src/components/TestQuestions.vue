<script setup>
import {inject, ref} from "vue";
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
let questionsLength = questions.value.length;
let totalQuestions;
switch (routeParams.game) {
    case "complete":
        totalQuestions = questionsLength * 5;
        break;
    case "match":
        totalQuestions = questions.value.reduce((sum, obj) => sum + Object.keys(obj).length, 0);
        break;
    default:
        totalQuestions = questionsLength;
}
const answerIsRight = ref("");
const answered = ref(false);
const questionsCont = ref(null);
const rightAnswers = ref(0);
const answeredQuestions = ref(0);
const inheritedVariables = {
    rightAnswers, 
    answeredQuestions,
    answered, 
    questions,
    changeAnswerIsRight(value) {answerIsRight.value = value},
    changeRightAnswers(value) {rightAnswers.value = value},
    changeAnswered(value) {answered.value = value}
};

function next() {
    answeredQuestions.value += 1;
    if (answeredQuestions.value >= questionsLength) {
        emit("result", routeParams.game, rightAnswers.value, totalQuestions);
        return;
    }
    answered.value = false;
}
</script>

<template>
    <div>
        <div class="progress">
            <div class="progress-bar" :style="{width: answeredQuestions * 100 / questionsLength + '%'}"></div>
        </div>
        <div ref="questionsCont" id="questions-cont">
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
        v-if="answered" 
        @click="answered && next()">
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