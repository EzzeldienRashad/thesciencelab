<script setup>
import {ref, provide, defineEmits} from "vue";
import GameSettings from "@/components/GameSettings.vue";
import TheTest from "@/components/TheTest.vue";
const questions = ref([]);
const level = ref("");
const emit = defineEmits(["changeTheme"]);

provide("level", level)
provide("questions", questions)

function startTest(levelStr, questionsArr) {
    level.value = levelStr;
    for (let i = questionsArr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [questionsArr[i], questionsArr[j]] = [questionsArr[j], questionsArr[i]];
    }
    switch (levelStr) {
        case "easy":
            questions.value = questionsArr.slice(0, Math.floor(questionsArr.length / 3));
            emit("changeTheme", "success");
            break;
        case "medium":
            questions.value = questionsArr.slice(0, Math.floor(questionsArr.length * 2 / 3));
            emit("changeTheme", "warning");
            break;
        case "hard":
            questions.value = questionsArr;
            emit("changeTheme", "danger");
            break;
    }
}
</script>

<template>
    <transition mode="out-in">
        <GameSettings @start="(level, questions) => startTest(level, questions)" v-if="!questions.length" />
        <TheTest @changeTheme="theme => $emit('changeTheme', theme)" v-else />
    </transition>
</template>

<style scoped>
.v-enter-active, .v-leave-active {
    transition: opacity 0.5s ease;
}
.v-enter-from, .v-leave-to {
    opacity: 0;
}
</style>