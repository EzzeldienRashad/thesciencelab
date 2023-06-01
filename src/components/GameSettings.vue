<script setup>
import {ref, nextTick} from "vue";
import {useRoute} from "vue-router";

defineEmits(["start"]);

const routeParams = useRoute().params
const game = routeParams.game;
const gameName = game.replaceAll("_", " ");
const units = ref([]);
const questions = ref([]);
let questionsNum;
const trans = ref(null);
const lessonsMaxHeight = ref("");

fetch(encodeURI("http://127.0.0.1/htdocs/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game))
    .then(res => res.json())
    .then(unitsJson => {
        units.value = unitsJson;
        nextTick(() => lessonsMaxHeight.value = trans.value.offsetHeight + "px");
    });

function getQuestions(unit) {
    fetch(encodeURI("http://127.0.0.1/htdocs/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit))
    .then(res => res.json())
    .then(questionsJson => questions.value = JSON.parse(questionsJson));
}
function scrollToTop() {
    scrollTo(0, 0);
}
</script>

<template>
    <div>
        <h2 class="text-center p-2">Welcome to the {{ gameName }} game!</h2>
        <transition name="lessons">
            <div class="border-top border-2 border-dark overflow-hidden" ref="trans" v-if="!questions.length">
                <p class="display-6">Please choose a unit:</p>
                <div class="row row-cols-1 row-cols-sm-2 g-2 p-2">
                    <div v-for="unit in units" :key="unit" class="col">
                        <button @click="getQuestions(unit)" class="btn btn-primary w-100 h-100 p-3">{{ unit }}</button>
                    </div>
                </div>
            </div>
        </transition>
        <transition name="levels" @before-enter="scrollToTop">
            <div class="overflow-hidden border-top border-2 border-dark" v-if="questions.length">
                <p class="display-6">Please choose a level:</p>
                <div class="d-flex flex-column align-items-center p2">        
                    <button @click="$emit('start', 'easy', questions)" class="btn btn-success w-75 p-3 m-1 text-center rounded-2">easy</button>
                    <button @click="$emit('start', 'medium', questions)" class="btn btn-warning w-75 p-3 m-1 text-center">medium</button>
                    <button @click="$emit('start', 'hard', questions)" class="btn btn-danger w-75 p-3 m-1 text-center">hard</button>
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
</style>