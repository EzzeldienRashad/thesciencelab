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
    <section dir="rtl" lang="ar">
        <h2>المشاركون فى الموقع:</h2>
        <div class="row p-2">
            <div v-for="uploader in uploaders" :key="uploader" class="fs-4 col-12 col-lg-6 p-1">{{ uploader[0] }}: {{ uploader[1] }} سؤال</div>
        </div>
    </section>
</template>