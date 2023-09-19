<script setup>
import {inject, ref, nextTick} from "vue";
import {useRoute} from "vue-router";
import ChooseGame from "@/components/ChooseGame.vue";
import CompleteGame from "@/components/CompleteGame.vue";
import RightOrWrongGame from "@/components/RightOrWrongGame.vue";
import MatchGame from "@/components/MatchGame.vue";
import rightSoundAudio from "@/assets/audio/right.mp3";
import wrongSoundAudio from "@/assets/audio/wrong.mp3";
import rightImg from "@/assets/icons/right.webp";
import wrongImg from "@/assets/icons/wrong.webp";

const nextArrow = ref(null);
const rightSound = ref(null);
const wrongSound = ref(null);
const audios = {
    right: rightSound, 
    wrong: wrongSound
}
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
    answeredQuestions,
    answered, 
    questions,
    changeAnswerIsRight(value) {
        answerIsRight.value = value;
        audios[value].value.play();
        setTimeout(() => answerIsRight.value = "", 750);
    },
    addRightAnswer() {rightAnswers.value += 1},
    changeAnswered() {
        answered.value = true;
        nextTick(() => document.getElementById("next-arrow").focus());
    }
};

function next() {
    answeredQuestions.value += 1;
    if (answeredQuestions.value >= questionsLength) {
        emit("result", rightAnswers.value, totalQuestions);
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
            <ChooseGame v-bind="inheritedVariables" v-if="routeParams.game == 'choose'" />
            <RightOrWrongGame v-bind="inheritedVariables" v-else-if="routeParams.game == 'right-or-wrong'" />
            <CompleteGame v-bind="inheritedVariables" v-else-if="routeParams.game == 'complete'" />
            <MatchGame v-bind="inheritedVariables" v-else-if="routeParams.game == 'match'" />
        </div>
        <img v-if="answerIsRight == 'right' && routeParams.game != 'right-or-wrong'" :src="rightImg" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <img v-if="answerIsRight == 'wrong' && routeParams.game != 'right-or-wrong'" :src="wrongImg" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio ref="rightSound" :src="rightSoundAudio"></audio>
        <audio ref="wrongSound" :src="wrongSoundAudio"></audio>
        <button 
        ref="nextArrow"
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