<script setup>
import {ref, inject} from "vue";
import BouncingBallsDecoration from "@/components/BouncingBallsDecoration.vue";
import GradeLink from "@/components/GradeLink.vue";
import logo from "@/assets/icons/logo.webp";

const grades = ref([
    "grade-4-first-term", "grade-4-second-term",
    "grade-5-first-term", "grade-5-second-term",
    "grade-6-first-term", "grade-6-second-term",
    "1st-prep-first-term", "1st-prep-second-term",
    "2nd-prep-first-term", "2nd-prep-second-term",
    "3rd-prep-first-term", "3rd-prep-second-term",
]);
const documentWidth = inject("documentWidth");
</script>

<template>
    <div>
        <section class="position-relative">
            <BouncingBallsDecoration />
            <div class="overlay overflow-hidden position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-around align-items-center">
                <h1 class="display-1 m-0 text-center fw-bold">
                    The<br/>
                    Science<br/>
                    Lab<br/>
                </h1>
                <img v-if="documentWidth > 500" :src="logo" alt="The Science Lab" width="230" height="230"/>
            </div>
        </section>
        <section class="p-4 p-sm-5">
            <h2 class="text-center">Welcome to the science lab!</h2>
            <p :class="['p-lg-3', documentWidth > 800 ? 'fs-5' : '']">
                Welcome to The Science Lab! The best place for learning Science!
                Here, you will find different collections of games for learning and practicing different Science lessons. 
                We offer a variety of games developed especially for Egyptian students to help them understand their lessons in an easier and funnier way. 
                We help you memorize the information more effectively. 
                We always try our best to improve our content and offer the best experience for our users. 
                We hope you a fun and intersting journey here in The Science Lab! 
            </p>
        </section>
        <section class="p-4 p-sm-5 row gx-0" data-cy="grades">
            <h2 class="col-12">Grades:</h2>
            <div v-for="grade in grades" :key="grade" class="col-12 col-md-6 p-2">
                <GradeLink :grade="grade"/>
            </div>
        </section>
    </div>
</template>

<style>
.overlay {
    background-color: rgba(0, 0, 0, 0.1);   
}
.overlay h1 {
    font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    color: transparent;
    background-clip: text;
    -webkit-background-clip: text;
    background-image: linear-gradient(to top, rgb(255, 0, 0), rgb(0, 255, 0) 33%, rgb(0, 0, 255) 66%, rgb(225, 0, 0) 100%);
    background-size: 100% 200%;
    animation-name: move-gradient;
    animation-duration: 5s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}
@keyframes move-gradient {
    to {
        background-position: 0% 200%;
    }
}
</style>