<script setup>
import {ref} from "vue";
import {useRoute} from "vue-router";

let units = ref([]);
defineEmits(["setUnit"]);

fetch("http://127.0.0.1/TheScienceLab/info/functions/printInfo.php?grade=" + useRoute().params.grade +
    "&game=" + useRoute().params.game)
    .then(res => res.json())
    .then(unitsArr => units.value = unitsArr);
</script>

<template>
    <h1 class="text-center">Please choose a unit</h1>
    <div class="row row-cols-1 row-cols-md-2 g-2">
        <div v-for="unit in units" :key="unit" class="col">
            <button @click="$emit('setUnit', unit)" class="text-decoration-none btn btn-primary w-100 p-2 py-3">
                {{ unit.replace(/\.[^/.]+$/, "") }}
            </button>
        </div>
    </div>
</template>