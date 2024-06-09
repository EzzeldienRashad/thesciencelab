<script setup>
const props = defineProps({
    title: String,
    description: String,
    center: Boolean
});
</script>

<template>
    <div class="col-sm-6 col-lg-3 mb-1 mb-sm-0">
        <div class="card h-100 w-100">
            <div class="card-body" :class="{'d-flex': center, 'align-items-center': center}">
                <h4 class="card-title">{{ title }}</h4>
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