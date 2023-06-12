<script setup>
import {ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import ControlPanelChoose from "@/components/ControlPanelChoose.vue";
import ControlPanelComplete from "@/components/ControlPanelComplete.vue";
import ControlPanelRightOrWrong from "@/components/ControlPanelRightOrWrong.vue";
import GradeUnits from "@/components/GradeUnits.vue";

const unit = ref("");
const routeParams = useRoute().params;
const router = useRouter();
const member = ref("");
const questions = ref([]);
const addingQuestion = ref(false);
const msg = ref("");
const msgColor = ref("");
const inheritedVariables = {
    questions,
    addingQuestion,
    msg,
    msgColor,
    deleteQuestion,
    addQuestion
}

fetch("http://127.0.0.1/info/functions/login.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.text())
    .then(memberValue => member.value = memberValue);

function loadQuestions() {
    fetch(encodeURI("http://127.0.0.1/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value))
    .then(res => res.json())
    .then(questionsArr => questions.value = questionsArr);
}
async function deleteQuestion(questionData, byKey = false) {
    fetch("http://127.0.0.1/info/functions/delete.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value + (byKey ? "&question=" + questionData : "&questionnum=" + questionData), {
        method: "get",
        credentials: "include"
    })
    .then(res => res.text())
    .then((msg) => {
        if (msg == "logout") router.push({name: "login"});
        else loadQuestions();
    });
}
function addQuestion() {
    const form = document.getElementById("form");
    fetch("http://127.0.0.1/info/functions/add.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value, {
        method: "post",
        credentials: "include",
        body: new FormData(form)
    })
    .then(res => res.text())
    .then(msgValue => {
        form.parentElement.scrollTo(0, 0);
        msgColor.value = "danger";
        if (msgValue == "logout") {
            msg.value = "You are not logged in.";
            setTimeout(() => router.push({name: "login"}), 1500);
        } else if (msgValue == "answernumerror") {
            msg.value = "Wrong number of answers!";
            setTimeout(() => msg.value = "", 1500);
        } else if (msgValue == "successful") {
            msgColor.value = "success"
            form.reset();
            msg.value = "Added successfully!";
            setTimeout(() => msg.value = "", 1000);
            loadQuestions();
        } else {
            msg.value = "An error occured.";
            setTimeout(() => msg.value = "", 1500);            
        }
    });
}
</script>

<template>
    <GradeUnits v-if="!unit" @setUnit="unitValue => {unit = unitValue; loadQuestions() }" />
    <div v-else>
        <header class="d-flex justify-content-between align-items-center pb-2 border-bottom border-2">
            <RouterLink to="/" class="text-dark">
                <font-awesome-icon icon="fa-solid fa-left-long" size="2x" />
            </RouterLink>
            <button @click="addingQuestion = !addingQuestion" class="btn btn-success">+ add</button>
        </header>
        <main class="d-flex flex-column-reverse pt-2">
            <ControlPanelChoose v-if="useRoute().params.game == 'choose'" v-bind="inheritedVariables" />
            <ControlPanelComplete v-if="useRoute().params.game == 'complete'" v-bind="inheritedVariables" />
            <ControlPanelRightOrWrong v-if="useRoute().params.game == 'right_or_wrong'" v-bind="inheritedVariables" />
        </main>
</div>
</template>