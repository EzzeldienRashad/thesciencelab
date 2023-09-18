<script setup>
import {ref, provide} from "vue";
import GameSettings from "@/components/GameSettings.vue";
import TheTest from "@/components/TheTest.vue";
const questions = ref([]);
const level = ref("");
const emit = defineEmits(["changeTheme"]);

provide("questions", questions);

function startTest(levelStr, questionsArr) {
    level.value = levelStr;
    switch (levelStr) {
        case "quick":
            questions.value = questionsArr.slice(0, Math.floor(questionsArr.length / 3));
            emit("changeTheme", "success");
            break;
        case "normal":
            questions.value = questionsArr.slice(0, Math.floor(questionsArr.length * 2 / 3));
            emit("changeTheme", "warning");
            break;
        case "coprehensive":
            questions.value = questionsArr;
            emit("changeTheme", "danger");
            break;
    }
}
</script>

<template>
    <transition mode="out-in">
        <GameSettings @start="(level, questions) => startTest(level, questions)" v-if="!Object.keys(questions).length" />
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