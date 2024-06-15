<script setup>
import {ref, nextTick} from "vue";
import { useRouter } from "vue-router";
import vueRouter from "@/modules/vue-router";
const {useRoute} = vueRouter;
import removeDashes from "@/modules/removeDashes.js";

defineEmits(["start"]);

const router = useRouter();
const routeParams = useRoute().params
const game = routeParams.game;
const units = ref([]);
const questions = ref([]);
const trans = ref(null);
const lessonsMaxHeight = ref("");
let currentTheme = 0;

fetch(encodeURI("http://127.0.0.1/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game))
    .then(res => res.json())
    .then(unitsJson => {
        if (unitsJson.length) units.value = unitsJson;
        else router.replace({name: "home"})
        nextTick(() => lessonsMaxHeight.value = trans.value.offsetHeight + "px");
    });

function getQuestions(unit) {
    fetch(encodeURI("http://127.0.0.1/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit))
    .then(res => res.json())
    .then(questionsArr => {
        if (routeParams.game == "complete") {
            questionsArr = shuffle(questionsArr.slice(0, (-questionsArr.length % 5 || undefined)));
            const questionGroups = [];
            for (let i = 0; i < questionsArr.length; i += 5) {
                questionGroups.push(questionsArr.slice(i, i + 5));
            }
            questions.value = questionGroups;
        } else {
            questions.value = shuffle(questionsArr);
        }
    });
}
function scrollToTop() {
    scrollTo(0, 0);
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
    <div>
        <h2 class="text-center p-2">Welcome to the {{ removeDashes(game) }} game!</h2>
        <transition name="lessons">
            <div class="border-top border-2 border-dark overflow-hidden" ref="trans" v-if="!Object.keys(questions).length">
                <p class="display-6">Please choose a unit:</p>
                <div class="row g-2 p-2" data-cy="units">
                    <template v-for="unit in units" :key="unit">
                        <div v-if="parseInt(unit) && currentTheme != parseInt(unit)" class="col-12">
                            <h3>Theme {{ currentTheme = parseInt(unit) }}:</h3>
                            <hr/>
                        </div>
                        <div class="col-sm-6">
                            <button :data-unit="unit" @click="getQuestions(unit)" class="btn btn-primary w-100 h-100 p-3 fs-5">{{ removeDashes(unit) }}</button>
                        </div>
                    </template>
                    <div class="col">
                        <button @click="getQuestions('whole')" class="btn btn-primary w-100 h-100 p-3 fs-5">The whole term</button>
                    </div>
                </div>
            </div>
        </transition>
        <transition name="levels" @before-enter="scrollToTop">
            <div class="overflow-hidden border-top border-2 border-dark" v-if="Object.keys(questions).length">
                <p class="display-6">Please choose the test type:</p>
                <div class="d-flex flex-column align-items-center p2">     
                    <button @click="$emit('start', 'easy', questions)" class="btn btn-success w-75 p-4 m-1 text-center rounded-4 fs-4 fw-bold">easy</button>
                    <button @click="$emit('start', 'medium', questions)" class="btn btn-warning w-75 p-4 m-1 text-center rounded-4 fs-4 fw-bold">medium</button>
                    <button @click="$emit('start', 'hard', questions)" class="btn btn-danger w-75 p-4 m-1 text-center rounded-4 fs-4 fw-bold">hard</button>
                    <button @click="$emit('start', 'mixed', questions)" class="btn btn-primary w-75 p-4 m-1 text-center rounded-4 fs-4 fw-bold">mixed</button>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.lessons-enter-active, .lessons-leave-active {
    transition: max-height 0.5s ease;
} 
.levels-enter-active, .levels-leave-active {
    transition: all 0.5s ease;
    transition-delay: 0.5s;
}
.lessons-enter-to, .lessons-leave-from {
    max-height: v-bind("lessonsMaxHeight");
}
.lessons-enter-from, .lessons-leave-to {
    max-height: 0;
}
.levels-enter-from, .levels-leave-to {
    max-height: 0;
}
.levels-enter-to, .levels-leave-from {
    max-height: 500px;
}
h3 {
    color: darkred;
}
</style>