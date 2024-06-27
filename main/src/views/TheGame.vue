<script setup>
import {ref, provide} from "vue";
import GameSettings from "@/components/GameSettings.vue";
import TheTest from "@/components/TheTest.vue";
const questions = ref([]);
const level = ref("");
const name = ref("");
const code = ref("");
const emit = defineEmits(["changeTheme"]);

provide("questions", questions);

function startTest(levelStr, questionsArr, nameValue, codeValue) {
    level.value = levelStr;
    switch (levelStr) {
        case "easy":
            if ((questions.value = questionsArr.filter(question => question["level"] == "easy")).length) emit("changeTheme", "success");
            break;
        case "medium":
            if ((questions.value = questionsArr.filter(question => question["level"] == "medium")).length) emit("changeTheme", "warning");
            break;
        case "hard":
            if ((questions.value = questionsArr.filter(question => question["level"] == "hard")).length) emit("changeTheme", "danger");
            break;
        case "mixed":
            questions.value = questionsArr.slice(0, Math.floor(questionsArr.length * 2 / 3));
            emit("changeTheme", "primary");
            break;
        case "all":
            questions.value = questionsArr;
            emit("changeTheme", "primary");
            if (nameValue) {
                name.value = nameValue;
            if (codeValue) {
                code.value = codeValue;
            }
            break;
        }
    }
}
</script>

<template>
    <transition mode="out-in">
        <GameSettings @start="(level, questions, name, code) => startTest(level, questions, name, code)" v-if="!Object.keys(questions).length" />
        <TheTest @changeTheme="theme => $emit('changeTheme', theme)" :name :code v-else />
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