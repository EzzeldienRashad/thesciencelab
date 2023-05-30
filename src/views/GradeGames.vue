<script setup>
import {useRoute} from "vue-router";
import {ref} from "vue";

const grade = useRoute().params.grade;
const gradeName = grade.replaceAll("_", " ");
const games = ref([]);

fetch("http://127.0.0.1/htdocs/info/functions/printInfo.php?grade=" + grade)
    .then(res => res.json())
    .then(gamesArray => games.value = gamesArray);
</script>

<template>
    <div>
        <h1 class="text-center pt-2">Welcome to {{gradeName}}!</h1>
        <h4 class="p-2">Please choose a game:</h4>
        <div class="row row-cols-sm-2 row-cols-lg-3 p-2">
            <div v-for="game in games" :key="game" class="col-12 col-sm-6 col-lg-4">
                <RouterLink class="d-flex flex-column justify-content-center align-items-center m-1 mb-2 d-inline-block text-decoration-none text-dark" :to="grade + '/' + game">
                    <figure class="mb-0 w-75">
                        <img class="w-100 border border-3 border-dark" :src="'/src/assets/images/' + game + '.webp'" :alt="game.replaceAll('_', ' ')">
                        <figcaption class="text-center fs-4 mt-2">
                            {{ game.replaceAll("_", " ") }}
                        </figcaption>
                    </figure>
                </RouterLink>
                <hr/>
            </div>
        </div>
    </div>
</template>
