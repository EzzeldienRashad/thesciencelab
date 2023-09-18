<script setup>
import {ref} from "vue";
import {removeDashes} from "@/modules/removeDashes.js"

const grades = ref([
    "grade-4-first-term", "grade-4-second-term",
    "grade-5-first-term", "grade-5-second-term",
    "grade-6-first-term", "grade-6-second-term",
    "1st-prep-first-term", "1st-prep-second-term",
    "2nd-prep-first-term", "2nd-prep-second-term",
    "3rd-prep-first-term", "3rd-prep-second-term",
]);
</script>

<template>
    <h1 class="text-center">Please choose a grade</h1>
    <div class="row row-cols-1 row-cols-md-2 g-2">
        <div v-for="grade in grades" :key="grade" class="col">
            <RouterLink :to="'/' + grade" class="text-decoration-none btn btn-primary w-100 p-2 py-3">
                {{ removeDashes(grade) }}
            </RouterLink>
        </div>
    </div>
</template>