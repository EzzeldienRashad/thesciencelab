<script setup>
import {ref} from "vue";
import removeDashes from "@/modules/removeDashes";

const books = ref(["physics-first-secondary-first-term", "physics-for-neet", "physics-high-school", "physics-second-secondary", "science-4th-primary-second-term", "science-5th-primary-second-term", "science-6th-primary-second-term"]);
</script>

<template>
    <div class="row p-4">
        <a :href="'http://127.0.0.1/info/books/' + book + '.pdf'" v-for="book in books" class="col-12 col-sm-6 col-md-4 col-xl-3 py-3 text-decoration-none fs-4 text-center">
                <img :src="'http://127.0.0.1/info/books/' + book + '.png'" class="w-100"/>
                <span class="d-block w-100">{{ removeDashes(book) }}</span>
            </a>
    </div>
</template>