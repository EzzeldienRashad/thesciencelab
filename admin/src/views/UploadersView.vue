<script setup>
import { ref } from "vue";

const uploaders = ref([]);

fetch("http://127.0.0.1/info/functions/uploadersCount.php", {
    method: "GET",
    credentials: "include"
})
    .then(resp => resp.json())
    .then(uploadersArr => uploaders.value = Object.entries(uploadersArr).sort((a, b) => b[1] - a[1]));
</script>

<template>
    <div class="row p-2" dir="rtl" lang="ar">
        <div v-for="uploader in uploaders" :key="uploader" class="fs-4 col-12 col-lg-6 border rounded-2 p-2 border-dark-subtle">{{ uploader[0] }}: {{ uploader[1] }} سؤال</div>
    </div>
</template>