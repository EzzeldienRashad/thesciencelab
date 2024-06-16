<script setup>
import {ref} from "vue";
import { useRoute } from "vue-router";
import removeDashes from "@/modules/removeDashes.js";
import chooseImg from "@/assets/images/choose.webp";
import completeImg from "@/assets/images/complete.webp";
import rightOrWrongImg from "@/assets/images/right-or-wrong.webp";
import matchImg from "@/assets/images/match.webp";
import biologyImg from "@/assets/images/biology.webp";
import physicsImg from "@/assets/images/physics.webp";
import chemistryImg from "@/assets/images/chemistry.webp";

const grade = useRoute().params.grade;
const gradeName = removeDashes(grade);
const games = ref(grade.includes("secondary") ? ["biology", "physics", "chemistry"] : ["choose", "right-or-wrong", "complete", "match"]);
const gamesImages = {
    "choose": chooseImg,
    "complete": completeImg,
    "right-or-wrong": rightOrWrongImg,
    "match": matchImg,
    "biology": biologyImg,
    "physics": physicsImg,
    "chemistry": chemistryImg,
};
</script>

<template>
    <div>
        <h1 class="text-center pt-2">{{ gradeName }}!</h1>
        <h4 class="p-2 text-center">Please choose a game</h4>
        <div class="row row-cols-lg-2 p-2 align-items-end">
            <div v-for="game in games" :key="game">
                <RouterLink class="d-flex flex-column justify-content-center align-items-center m-1 mb-2 d-inline-block text-decoration-none text-dark" :to="'/' + grade + '/' + game">
                    <figure class="mb-0 w-75">
                        <img class="w-100 border border-3 border-dark" :src="gamesImages[game]" :alt="removeDashes(game)">
                        <figcaption class="text-center fs-4 mt-2">
                            {{ removeDashes(game) }}
                        </figcaption>
                    </figure>
                </RouterLink>
                <hr/>
            </div>
        </div>
    </div>
</template>