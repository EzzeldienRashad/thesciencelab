<script setup>
import {ref} from "vue";
import {useRouter} from "vue-router";
import vueRouter from "@/modules/vue-router";
const {useRoute} = vueRouter;
import removeDashes from "@/modules/removeDashes.js";

const router = useRouter();
let units = ref([]);
let currentTheme = 0;
defineEmits(["setUnit"]);

fetch("http://127.0.0.1/thesciencelab/info/functions/printInfo.php?grade=" + useRoute().params.grade +
    "&game=" + useRoute().params.game)
    .then(res => res.json())
    .then(unitsArr => {
        if (unitsArr.length) units.value = unitsArr;
        else router.replace({name: "home"})
    });
</script>

<template>
    <h1 class="text-center">Please choose a unit</h1>
    <div class="row g-2" data-cy="units">
        <template v-for="unit in units" :key="unit">
            <div v-if="parseInt(unit) && currentTheme != parseInt(unit)" class="col-12">
                <h3>Theme {{ currentTheme = parseInt(unit) }}:</h3>
                <hr/>
            </div>
            <div class="col-sm-6">
                <button @click="$emit('setUnit', unit)" class="text-decoration-none btn btn-primary w-100 h-100 p-2 py-3 fs-5">
                    {{ removeDashes(unit) }}
                </button>
            </div>
        </template>
    </div>
</template>

<style scoped>
h3 {
    color: darkred;
}
</style>