<script setup>
import { useRouter } from "vue-router";
import vueRouter from "@/modules/vue-router";
const {useRoute} = vueRouter;
const props = defineProps({
    score: Number,
    total: Number
});
const routeParams = useRoute().params;
const router = useRouter();
const result = Math.round((props.score / props.total * 100 + Number.EPSILON) * 100) / 100;
let info = [];
if (result >= 85) {
    info = ["excellent", "darkgreen", "WOW! That's exceptional! You got almost all of the questions right! You're on the right way to becoming a great scientist. Do not waste your abilities. Now, it's time to discover more in the world of science and help other people solve their problems. Good luck!"];
} else if (result >= 75) {
    info = ["very good", "green", "Well done! That was awesome! You got most of the questions right. You're on your way to becoming a science expert. All you need is more practice and solving quizes. Never give up!"];
} else if (result >= 65) {
    info = ["good", "lightgreen", "Good work! You got most of the questions right! This is a good indicator that you understand the lesson, but you still miss some information. You should solve more tests and revise from time to time."];
} else if (result >= 50) {
    info = ["fair", "orange", "You got fair. This means that you have a good understanding of the lessons, but you still miss a lot of questions. You need to revise the lessons and solve more tests before comming back again :)"];
} else if (result >= 35) {
    info = ["fail", "red", "You got most of the questions wrong. This means you didn't study hard. You should study with more focus and memorize the information well, then come here and measure your level again."];
} else {
    info = ["very bad", "maroon", "Unfortunately, you got a bad score. You don't seem to have studied your lessons well. Study again, the come here to measure how your score has become."];
}
const gradient = "conic-gradient(" + info[1] + 
    " 0%, " + info[1] + " " + props.score / props.total * 100 + "%, #f8f9fa " + 
    props.score / props.total * 100 + "%, #f8f9fa 100%";

function back() {
    router.push({path: "/" + routeParams.grade});
}
</script>

<template>
    <div>
        <div class="row p-3 p-md-5">
            <div class="col-md-6">
                <div id="c-outer" class="mx-auto border border-2 border-dark rounded-circle position-relative">
                    <div id="c-inner" class="border border-2 rounded-circle position-absolute top-50 start-50 translate-middle bg-light d-flex fs-4 align-items-center justify-content-center">
                        <div id="score" :style="{'color': info[1]}" class="d-inline-block fs-2" data-cy="score">{{ score }} / {{ total }}</div>
                    </div>
                </div>
                <h1 :style="{'color': info[1]}" class="text-center my-3" data-cy="score-text">{{ info[0] }}</h1>
            </div>
            <div class="col-md-6 fs-4">
                <div class="float-start separator d-none d-md-inline-block h-100 bg-black"></div>
                <div class="ms-4">{{ info[2] }}</div>
            </div>
        </div>
        <div class="d-flex justify-content-center my-3">
            <RouterLink @click.prevent="back" :to="'/' + routeParams.grade + '/' + routeParams.game" class="d-inline-block p-4 fs-1 rounded-3 text-bg-primary text-decoration-none">
                Continue &nbsp;&nbsp;&nbsp;<font-awesome-icon icon="fa-solid fa-right-long" class="text-light"/>
            </RouterLink>
        </div>
    </div>
</template>

<style scoped>
#c-outer {
    width: 200px;
    height: 200px;
    background-image: v-bind(gradient); 
}
#c-inner {
    width: 170px;
    height: 170px;
}
.separator {
    width: 5px;
}
</style>