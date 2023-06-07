<script setup>
import {ref} from "vue";
import {useRoute} from "vue-router";
import ControlPanelChoose from "@/components/ControlPanelChoose.vue";
import ControlPanelComplete from "@/components/ControlPanelComplete.vue";
import ControlPanelRightOrWrong from "@/components/ControlPanelRightOrWrong.vue";
import GradeUnits from "@/components/GradeUnits.vue";

const unit = ref("");
</script>

<template>
    <GradeUnits v-if="!unit" @setUnit="unitValue => unit = unitValue " />
    <ControlPanelChoose v-else-if="useRoute().params.game == 'choose'" :unit="unit" />
    <ControlPanelComplete v-else-if="useRoute().params.game == 'complete'" :unit="unit" />
    <ControlPanelRightOrWrong v-else-if="useRoute().params.game == 'right_or_wrong'" :unit="unit" />
</template>