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
        case "easy":
            questions.value = questionsArr.filter(question => question["level"] == "easy");
            emit("changeTheme", "success");
            break;
        case "medium":
            questions.value = questionsArr.filter(question => question["level"] == "medium");
            emit("changeTheme", "warning");
            break;
        case "hard":
            questions.value = questionsArr.filter(question => question["level"] == "hard");
            emit("changeTheme", "danger");
            break;
        case "mixed":
            questions.value = questionsArr.slice(0, Math.floor(questionsArr.length * 2 / 3));
            emit("changeTheme", "primary");
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