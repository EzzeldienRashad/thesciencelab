<script setup>
import {ref, nextTick} from "vue";
import {useRoute, useRouter} from "vue-router";
import { removeDashes } from "@/modules.js";

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
    .then(questionsArr => questions.value = questionsArr);
}
function scrollToTop() {
    scrollTo(0, 0);
}
</script>

<template>
    <div>
        <h2 class="text-center p-2">Welcome to the {{ removeDashes(game) }} game!</h2>
        <transition name="lessons">
            <div class="border-top border-2 border-dark overflow-hidden" ref="trans" v-if="!Object.keys(questions).length">
                <p class="display-6">Please choose a unit:</p>
                <div class="row g-2 p-2">
                    <template v-for="unit in units" :key="unit">
                        <div v-if="parseInt(unit) && currentTheme != parseInt(unit)" class="col-12">
                            <h3>Theme {{ currentTheme = parseInt(unit) }}:</h3>
                            <hr/>
                        </div>
                        <div class="col-sm-6">
                            <button @click="getQuestions(unit)" class="btn btn-primary w-100 h-100 p-3">{{ removeDashes(unit.replace(/\.[^/.]+$/, "")) }}</button>
                        </div>
                    </template>
                    <div class="col">
                        <button @click="getQuestions('whole')" class="btn btn-primary w-100 h-100 p-3">The whole term</button>
                    </div>
                </div>
            </div>
        </transition>
        <transition name="levels" @before-enter="scrollToTop">
            <div class="overflow-hidden border-top border-2 border-dark" v-if="Object.keys(questions).length">
                <p class="display-6">Please choose the test type:</p>
                <div class="d-flex flex-column align-items-center p2">     
                    <button @click="$emit('start', 'quick', questions)" class="btn btn-success w-75 p-3 m-1 text-center rounded-2">quick</button>
                    <button @click="$emit('start', 'normal', questions)" class="btn btn-warning w-75 p-3 m-1 text-center">normal</button>
                    <button @click="$emit('start', 'coprehensive', questions)" class="btn btn-danger w-75 p-3 m-1 text-center">coprehensive</button>
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
    max-height: 270px;
}
h3 {
    color: darkred;
}
</style>