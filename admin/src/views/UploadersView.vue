<script setup>
import { ref } from "vue";

const uploaders = ref([]);

fetch("http://127.0.0.1/info/functions/uploaders.php", {
    method: "GET",
    credentials: "include"
})
    .then(resp => resp.json())
    .then(uploadersArr => uploaders.value = Object.entries(uploadersArr).sort((a, b) => b[1] - a[1]));
</script>

<template>
    <div v-for="uploader in uploaders" :key="uploader" class="card" dir="rtl">
        <div class="card-header fs-5">{{ uploader[0] }}: {{ uploader[1] }} سؤال</div>
    </div>
</template>