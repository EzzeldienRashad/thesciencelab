<script setup>
import {RouterLink} from 'vue-router';
import {onMounted, ref, inject} from "vue";
import {removeDashes} from "@/modules.js";

const props = defineProps({
    grade: {
        type: String,
        required: true
    }
});
let gradeName = ref("");
const documentWidth = inject("documentWidth");

onMounted(() => {
    gradeName.value = removeDashes(props.grade);
});
</script>

<template>
    <RouterLink :class="{'fs-4': documentWidth > 500}" class="m-1 p-2 border-dark d-inline-block border border-3 text-decoration-none text-dark w-100" :to="'/' + grade">
        {{ gradeName }}
        <font-awesome-icon class="float-end" icon="fa-solid fa-right-long" size="2x"/>
    </RouterLink>
</template>