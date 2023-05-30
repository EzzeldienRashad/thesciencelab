<script setup>
import {useRoute} from "vue-router";
import ChooseQuestions from "@/components/ChooseQuestions.vue";
import RightOrWrongQuestions from "@/components/RightOrWrongQuestions.vue";
import CompleteQuestions from "@/components/CompleteQuestions.vue";

const routeParams = useRoute().params;
</script>

<template>
    <div>
        <ChooseQuestions @result="(game, score, total) => $emit('result', game, score, total)" v-if="routeParams.game == 'choose'" />
        <RightOrWrongQuestions v-if="routeParams.game == 'right_or_wrong'" />
        <CompleteQuestions v-if="routeParams.game == 'complete'" />
    </div>
</template>