<script setup>
import {onMounted, ref} from "vue"

const props = defineProps({
    title: String,
    description: String,
    center: Boolean
});
const cardTitle = ref(null);

onMounted(() => {
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += Math.floor(Math.random() * 10);
    }
    cardTitle.value.style.color = color;
});
</script>

<template>
    <div class="col-sm-6 col-lg-3 mb-1 mb-sm-0">
        <div class="card h-100 w-100">
            <div class="card-body" :class="{'d-flex': center, 'align-items-center': center}">
                <h4 class="card-title" ref="cardTitle">{{ title }}</h4>
                <p class="card-text fs-5">{{ description }}</p>
            </div>
        </div>
    </div>
</template>

<style>
.next-arrow {
    transform: translateY(-10%);
}
</style>