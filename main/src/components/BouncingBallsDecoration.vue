<script setup>
import {ref, onMounted} from "vue";
import { onBeforeRouteLeave } from "vue-router";

const ballId = ref(0);
const balls = ref([]);
const image = ref(null);
let maxBallsNumber = 17;

onMounted(createBall);

function createBall() {
    let createsBall = true;
    if (balls.value.length >= maxBallsNumber || !image.value) return
    const diameter = 20;
    const horizontalSpeed = Math.floor(Math.random() + 1);
    let verticalSpeed = Math.floor(Math.random() + 1) * [-1, 1][Math.floor(Math.random() * 2)];
    const left = ref(0);
    const top = ref(Math.random() * (image.value.clientHeight - diameter));
    const letters = '0123456789ABCDEF';
    let backgroundColor = '#';
    for (let i = 0; i < 6; i++) {
        backgroundColor += letters[Math.floor(Math.random() * 16)];
    }
    ballId.value++;
    let ball = ref({
        id: ballId.value,
        color: backgroundColor,
        left: left.value,
        top: top.value
    });
    function moveBall() {
        if (!image.value) return;
        ball.value.left += horizontalSpeed;
        ball.value.top += verticalSpeed;
        if (ball.value.top + diameter >= image.value.clientHeight || ball.value.top <= 0) {
            verticalSpeed = -verticalSpeed;
        }
        if (ball.value.left > 70 && createsBall) {
            createBall()
            createsBall = false;
        }
        if (ball.value.left > image.value.clientWidth) {
            balls.value.splice(balls.value.map(el => el.value.id).indexOf(ball.value.id), 1);
            moveBall = undefined;
        } else {
            requestAnimationFrame(moveBall);
        }
    };
    balls.value.push(ball);
    requestAnimationFrame(moveBall)
}
</script>

<template>
    <div class="game position-relative w-100">
        <div class="image top-0 start-0 overflow-hidden w-100 h-100" ref="image">
            <div v-for="ball in balls" :key="ball.value.id" :style="{backgroundColor: ball.value.color, left: ball.value.left + 'px', top: ball.value.top + 'px'}" class="ball position-absolute rounded-circle"></div>
        </div>
    </div>
</template>

<style scoped>
.game {
    height: 50vh;
}
.image {
    background-image: url("@/assets/images/scienceBackground.webp");
    background-size: cover;
    opacity: 0.9;
    filter: blur(2px);
    position: absolute;
}
.ball {
    width: 20px;
    height: 20px;
}
</style>