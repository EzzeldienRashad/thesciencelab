<script setup>
import {RouterLink} from 'vue-router';
import {onMounted, ref, defineProps} from "vue";

const props = defineProps({
    grade: {
        type: String,
        required: true
    }
});
let gradeName = ref("");

onMounted(() => {
    gradeName.value = props.grade.replaceAll("_", " ");//capitalize first letter
});
</script>

<template>
    <RouterLink class="m-1 p-2 border-dark d-inline-block border border-3 text-decoration-none text-dark w-100" :to="grade">
        {{ gradeName }}
        <font-awesome-icon class="float-end" icon="fa-solid fa-right-long" size="2x"></font-awesome-icon>
    </RouterLink>
</template>