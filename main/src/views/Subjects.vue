<script setup>
import {useRoute} from "vue-router";
import removeDashes from "@/modules/removeDashes.js";

const grade = useRoute().params.grade;
const gradeName = removeDashes(grade);
</script>

<template>
    <div>
        <h1 class="text-center pt-2">Welcome to {{ gradeName }}!</h1>
        <h4 class="p-2">Please choose a Subject:</h4>
        <div class="row row-cols-sm-2 row-cols-lg-3 p-1">
            <div class="col-12 col-sm-6 col-lg-4">
                <RouterLink class="d-flex flex-column justify-content-center align-items-center m-1 mb-2 d-inline-block text-decoration-none text-dark" :to="'/' + grade + '/biology'">
                    <figure class="mb-0 w-75">
                        <img class="w-100 border border-3 border-dark" src="@/assets/images/biology.webp" :alt="Biology">
                        <figcaption class="text-center fs-4 mt-2">Biology</figcaption>
                    </figure>
                </RouterLink>
                <hr/>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <RouterLink class="d-flex flex-column justify-content-center align-items-center m-1 mb-2 d-inline-block text-decoration-none text-dark" :to="'/' + grade + '/physics'">
                    <figure class="mb-0 w-75">
                        <img class="w-100 border border-3 border-dark" src="@/assets/images/physics.webp" :alt="physics">
                        <figcaption class="text-center fs-4 mt-2">physics</figcaption>
                    </figure>
                </RouterLink>
                <hr/>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <RouterLink class="d-flex flex-column justify-content-center align-items-center m-1 mb-2 d-inline-block text-decoration-none text-dark" :to="'/' + grade + '/chemistry'">
                    <figure class="mb-0 w-75">
                        <img class="w-100 border border-3 border-dark" src="@/assets/images/chemistry.webp" :alt="chmeistry">
                        <figcaption class="text-center fs-4 mt-2">chemistry</figcaption>
                    </figure>
                </RouterLink>
                <hr/>
            </div>
        </div>
    </div>
</template>