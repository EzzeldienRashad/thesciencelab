<script setup>
import {ref} from "vue";
import removeDashes from "@/modules/removeDashes";

const books = ref({
    "physics-first-secondary-first-term": "https://drive.google.com/file/d/1H_-weqmApWHS5n87vhVha1sWfenFfMb2/view?usp=drive_link", 
    "physics-for-neet": "https://drive.google.com/file/d/1SSRkgivkMn5gw1h9jtL6tASxzumnz3xw/view?usp=drive_link", 
    "physics-high-school": "https://drive.google.com/file/d/1Kq5zneNgmFGrugOSL6HbG7I4ebRvFeQ9/view?usp=drive_link", 
    "physics-second-secondary": "https://drive.google.com/file/d/1TQ3ZyZFvZ550xVCdUzyygPlXBpRHiVzj/view?usp=drive_link", 
    "science-4th-primary-second-term": "https://drive.google.com/file/d/1t3FPlSt6_JsMd8TLMjtjX6Mmk5VnjR2t/view?usp=drive_link", 
    "science-5th-primary-second-term": "https://drive.google.com/file/d/1y3qRLog-7BztylepYEyQV6XNxoTvZrUO/view?usp=drive_link", 
    "science-6th-primary-second-term": "https://drive.google.com/file/d/18pyZfUoo5vS6bAG2nnc7kvUvGSY_3BvV/view?usp=drive_link"}
);
</script>

<template>
    <div class="row p-4">
        <a v-for="book in Object.keys(books)" :href="books[book]" class="col-12 col-sm-6 col-md-4 col-xl-3 py-3 text-decoration-none fs-4 text-center">
                <img :src="'http://127.0.0.1/info/books/' + book + '.png'" class="w-100"/>
                <span class="d-block w-100">{{ removeDashes(book) }}</span>
            </a>
    </div>
</template>