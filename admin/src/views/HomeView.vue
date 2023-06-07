<script setup>
import {ref} from "vue";

const grades = ref([]);

fetch("http://127.0.0.1/TheScienceLab/info/functions/printInfo.php")
    .then(res => res.json())
    .then(gradesArr => grades.value = gradesArr);
</script>

<template>
    <h1 class="text-center">Please choose a grade</h1>
    <div class="row row-cols-1 row-cols-md-2 g-2">
        <div v-for="grade in grades" :key="grade" class="col">
            <RouterLink :to="grade" class="text-decoration-none btn btn-primary w-100 p-2 py-3">
                {{ grade.charAt(0).toUpperCase() + grade.slice(1).replaceAll("_", " ") }}
            </RouterLink>
        </div>
    </div>
</template>