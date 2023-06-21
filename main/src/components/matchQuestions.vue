<script setup>
import {ref, nextTick} from "vue";

const props = defineProps(["rightAnswers", "answeredQuestions", "answered", "questions", "changeAnswerIsRight", "changeRightAnswers", "changeAnsweredQuestions", "changeAnswered"]);
const {rightAnswers, answeredQuestions, answered, questions, changeAnswerIsRight, changeRightAnswers, changeAnsweredQuestions, changeAnswered} = props;
const shuffledQuestionGroups = shuffle(questions.value);
const shuffledQuestions = [];
for (let questionGroup of shuffledQuestionGroups) {
    const shuffledKeys = shuffle(Object.keys(questionGroup));
    const shuffledValues = shuffle(Object.values(questionGroup));
    shuffledQuestions.push({"questions": shuffledKeys, "answers": shuffledValues});
}
const checked = ref(false);
let rightSound;
let wrongSound;

nextTick(() => {
    rightSound = document.getElementById("rightSound");
    wrongSound = document.getElementById("wrongSound");
});

function checkAnswer() {

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
    <div class="vw-100 p-2 p-sm-3 p-md-5 overflow-hidden" v-for="n in shuffledQuestions.length" :key="n">
        <div class="question pb-3 table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="text-center">Column A</th>
                        <th class="text-center">Column B</th>
                    </tr>
                    <tr v-for="c in shuffledQuestions[n - 1]['questions'].length" :key="c">
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div :data-text="shuffledQuestions[n - 1]['questions'][c - 1]" class="flex-grow-1 border-end border-2 pe-2">
                                    {{ shuffledQuestions[n - 1]['questions'][c - 1] }}
                                </div>
                                <div class="pe-4 ps-1">
                                    <div class="connect-line position-relative bg-danger ms-2">
                                        <div class="circle-outer circle-outer-start d-flex align-items-center justify-content-center bg-warning rounded-circle position-absolute top-0 start-0">
                                            <div class="circle-inner bg-primary rounded-circle position-relative"></div>
                                        </div>
                                        <div class="circle-outer circle-outer-end d-flex align-items-center justify-content-center bg-warning rounded-circle position-absolute top-0 end-0">
                                            <div class="circle-inner bg-primary rounded-circle position-relative"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="ps-4 pe-1 border-end border-2 me-2">
                                    <div class="circle-outer d-flex align-items-center justify-content-center bg-warning rounded-circle">
                                        <div class="circle-inner bg-primary rounded-circle"></div>
                                    </div>
                                </div>
                                <div :data-text="shuffledQuestions[n - 1]['answers'][c - 1]" class="flex-grow-1">
                                    {{ shuffledQuestions[n - 1]['answers'][c - 1] }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button @click="!answered && checkAnswer()" :disabled="answered" class="w-100 p-2 h-3 text-bg-primary rounded-2 fw-bold" :class="{'opacity-dec': answered}">done</button>
    </div>
</template>

<style scoped>
button {
    transition: opacity 0.5s ease;
}
img {
    max-width: 200px;
}
.opacity-dec {
    opacity: 0.7;
}
td {
    min-width: 15rem;
    vertical-align: middle;
}
.circle-outer {
    width: 1.3rem;
    height: 1.3rem;
}
.circle-inner {
    width: 0.8rem;
    height: 0.8rem
}
.connect-line {
    height: 0.8rem;
}
.circle-outer-start {
    transform: translate(-50%, -0.25rem);
}
.circle-outer-end {
    transform: translate(50%, -0.25rem);
}
</style>