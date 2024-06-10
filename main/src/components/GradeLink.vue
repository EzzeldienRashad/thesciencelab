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

onMounted(() => {
    gradeName.value = removeDashes(props.grade);
});
</script>

<template>
    <div class="accordion" id="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fs-4" type="button" data-bs-toggle="collapse" :data-bs-target="'#' + grade" aria-expanded="false" :aria-controls="grade">
                    {{ removeDashes(grade) }}
                </button>
            </h2>
            <div :id="grade" class="accordion-collapse collapse" data-bs-parent="#accordion">
                <div class="accordion-body row">
                    <RouterLink class="text-decoration-none fs-5 col-6 border-end border-2" :to="'/' + grade + '-first-term'">
                        First Term
                        <font-awesome-icon class="float-end next-arrow" icon="fa-solid fa-right-long" size="2x"/>
                    </RouterLink>
                    <RouterLink class="text-decoration-none fs-5 col-6" :to="'/' + grade + '-second-term'">
                        Second Term
                        <font-awesome-icon class="float-end next-arrow" icon="fa-solid fa-right-long" size="2x"/>
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.next-arrow {
    transform: translateY(-10%);
}
</style>