<script setup>
import { ref } from "vue";
import removeDashes from "@/modules/removeDashes.js";

const uploadedQuestions = ref([]);

fetch("http://127.0.0.1/info/functions/uploadedQuestionsCount.php", {
    method: "GET",
    credentials: "include"
})
    .then(resp => resp.json())
    .then(uploadedQuestionsArr => uploadedQuestions.value = Object.entries(uploadedQuestionsArr).sort((a, b) => b[1] - a[1]));
</script>

<template>
    <div class="row p-2">
        <div v-for="uploadedQuestion in uploadedQuestions" :key="uploadedQuestion" class="fs-4 col-12 col-lg-6 border rounded-2 p-2 border-dark-subtle">{{ removeDashes(uploadedQuestion[0]) }}: {{ uploadedQuestion[1] }} questions</div>
    </div>
</template>