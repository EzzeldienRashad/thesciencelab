<script setup>
import {inject, ref, nextTick, defineEmits} from "vue";

const emit = defineEmits(["result"]);
const questions = inject("questions");
const answer = ref("");
const rightSound = ref(null);
const wrongSound = ref(null);
const answered = ref(false);
const transitioning = ref(false);
const questionWidth = ref(document.documentElement.offsetWidth);
let questionsCont;
nextTick(() => questionsCont = document.getElementById("questions-cont"));
const transitionDuration = "1s";
const rightAnswers = ref(0);
const answeredQuestions = ref(0);

function checkAnswer(event, rightAnswer) {
    if (event.target.tagName != "BUTTON") return;
    if (event.target.dataset.choice == JSON.parse(event.target.parentElement.dataset.choices)[rightAnswer - 1]) {
        answer.value = "right";
        rightSound.value.play();
        event.target.style.border = "5px solid green";
        setTimeout(() => answer.value = "", 750);
        rightAnswers.value++;
    } else {
        answer.value = "wrong";
        wrongSound.value.play();
        event.target.style.border = "5px solid red";
        document.querySelector("button[data-choice='" + JSON.parse(event.target.parentElement.dataset.choices)[rightAnswer - 1] + "']").style.border = "5px solid green";
        setTimeout(() => answer.value = "", 750);
    }
    answeredQuestions.value++;
    answered.value = true;
    Array.from(event.target.parentElement.parentElement.querySelectorAll("button")).forEach((el) => el.style.pointerEvents = "none");
}
function next() {
    if (answeredQuestions.value >= questions.value.length) {
        emit("result", "choose", rightAnswers.value, answeredQuestions.value);
        return;
    }
    transitioning.value = true;
    questionWidth.value = questionsCont.firstElementChild.offsetWidth;
    questionsCont.style.marginLeft = questionsCont.offsetLeft - questionWidth.value + "px";
    setTimeout(() => {
        transitioning.value = false;
        answered.value = false;
        const row = document.querySelector(".row");
        row.parentElement.style.height = row.parentElement.offsetHeight + "px";
        row.remove();
    }, parseFloat(transitionDuration) * 1000);
}
</script>

<template>
    <div>
        <div id="questions-cont" class="d-flex overflow-hidden">
            <div class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden" v-for="question in questions" :key="question[0]">
                <h2 class="mb-5">{{ question[0] }}</h2>
                <div @click="$event => !answered && checkAnswer($event, question[2])" class="row gx-0">
                    <div 
                        v-for="choice in question[1]" 
                        :key="choice"
                        class="col-sm-6 p-2"
                        :data-choices="JSON.stringify(question[1])">
                        <button class="btn w-100 p-2 py-3" :data-choice="choice">{{ choice }}</button>
                    </div>
                </div>
            </div>
        </div>
        <img v-if="answer == 'right'" src="@/assets/icons/right.webp" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio ref="rightSound" src="/src/assets/audio/right.mp3"></audio>
        <img v-if="answer == 'wrong'" src="@/assets/icons/wrong.webp" width="200" height="200" class="position-fixed start-50 translate-middle-x"/>
        <audio ref="wrongSound" src="/src/assets/audio/wrong.mp3"></audio>
        <font-awesome-icon v-if="answered && !transitioning" @click="!transitioning && answered && next()" id="next-arrow" icon="fa-solid fa-right-long" size="3x" role="button" class="position-fixed text-primary top-50 translate-middle-y"/>
    </div>
</template>

<style scoped>
div#questions-cont {
    width: max-content;
    transition: margin-left v-bind(transitionDuration) ease;
}
button {
    background-color: lightgrey;
}
button:hover {
    background-color: rgb(233, 233, 233);
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