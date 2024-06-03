<script setup>
import {ref} from "vue";
import { onBeforeRouteLeave } from "vue-router";
import GamesContainer from "@/components/GamesContainer.vue";
import TestResult from "@/components/TestResult.vue";

const emit = defineEmits(["changeTheme"]);
const state = ref("READY...");
const started = ref(false);
const done = ref(false);
const score = ref(0);
const total = ref(0);

setTimeout(() => state.value = "SET...", 500);
setTimeout(() => state.value = "GO!", 1000);
setTimeout(() => started.value = true, 1500);

function result(scoreValue, totalValue) {
    score.value = scoreValue;
    total.value = totalValue;
    done.value = true;
}

onBeforeRouteLeave(() => {
    emit("changeTheme", "primary");
});
</script>

<template>
    <transition mode="out-in">
        <h1 v-if="!started" class="vw-100 vh-100 d-flex align-items-center justify-content-center position-fixed">
            <div>{{ state }}</div>
        </h1>
        <GamesContainer @result="(score, total) => result(score, total)" v-else-if="started && !done" />
        <TestResult :score="score" :total="total" v-else-if="started && done" />
    </transition>
</template>

<style scoped>
h1 {
    font-size: 50px;
    transform: translateY(-30px);
}
.v-enter-active, .v-leave-active {
    transition: opacity 0.7s ease;
}
.v-enter-from, .v-leave-to {
    opacity: 0;
}
</style>