<script setup>
import { ref } from "vue";
import removeDashes from "@/modules/removeDashes.js";

const uploadedQuestions = ref([]);

fetch("http://127.0.0.1/thesciencelab/info/functions/uploadedQuestionsCount.php", {
    method: "GET",
    credentials: "include"
})
    .then(resp => resp.json())
    .then(uploadedQuestionsArr => uploadedQuestions.value = Object.entries(uploadedQuestionsArr).sort((a, b) => b[1] - a[1]));
</script>

<template>
    <section>
        <h2 class="text-start">Uploaded Questions:</h2>
        <div class="row p-2">
            <div v-for="uploadedQuestion in uploadedQuestions" :key="uploadedQuestion" class="fs-4 col-12 col-lg-6 p-1">{{ removeDashes(uploadedQuestion[0]) }}: {{ uploadedQuestion[1] }} questions</div>
        </div>
    </section>
</template>