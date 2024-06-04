<script setup>
import { RouterLink } from "vue-router";
import {onMounted, ref, inject} from "vue";
import removeDashes from "@/modules/removeDashes.js";

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
    <RouterLink :class="{'fs-5': documentWidth > 760 && documentWidth < 830, 'fs-4': (documentWidth > 500 && documentWidth < 760) || documentWidth > 830}" class="p-2 border-dark border border-3 text-decoration-none text-dark w-100 d-block" :to="'/' + grade">
        {{ gradeName }}
        <font-awesome-icon class="float-end next-arrow" icon="fa-solid fa-right-long" size="2x"/>
    </RouterLink>
</template>

<style>
.next-arrow {
    transform: translateY(-10%);
}
</style>