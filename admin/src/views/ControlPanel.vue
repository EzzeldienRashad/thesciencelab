<script setup>
import {ref} from "vue";
import {useRouter, useRoute} from "vue-router";
import ControlPanelChoose from "@/components/ControlPanelChoose.vue";
import ControlPanelComplete from "@/components/ControlPanelComplete.vue";
import ControlPanelRightOrWrong from "@/components/ControlPanelRightOrWrong.vue";
import ControlPanelMatch from "@/components/ControlPanelMatch.vue";
import GradeUnits from "@/components/GradeUnits.vue";

const unit = ref("");
const uploaders = ref({});
const routeParams = useRoute().params;
const router = useRouter();
const member = ref("");
const questions = ref([]);
const msg = ref("");
const msgColor = ref("");
const inheritedVariables = {
    questions,
    msg,
    msgColor,
    deleteQuestion,
    addQuestion,
    member,
    uploaders
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
    if (member.value == "admin") {
        fetch("http://127.0.0.1/info/functions/printUploaders.php?grade=" + routeParams.grade +
            "&game=" + routeParams.game + "&unit=" + unit.value, {
                method: "get",
                credentials: "include"
            })
            .then(res => res.json())
            .then(uploadersArr => uploaders.value = uploadersArr);
    }
}
async function deleteQuestion(questionData) {
    fetch("http://127.0.0.1/info/functions/delete.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value + "&questionnum=" + questionData, {
        method: "get",
        credentials: "include"
    })
    .then(res => res.text())
    .then((msg) => {
        if (msg == "logout") router.push({name: "login"});
        else loadQuestions();
    });
}
function addQuestion(form) {
    fetch("http://127.0.0.1/info/functions/add.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value, {
        method: "post",
        credentials: "include",
        body: new FormData(form)
    })
    .then(res => res.text())
    .then(msgValue => {
        console.log(msgValue)
        form.parentElement.scrollTo(0, 0);
        msgColor.value = "danger";
        if (msgValue == "logout") {
            msg.value = "You are not logged in.";
            setTimeout(() => router.push({name: "login"}), 1500);
        } else if (msgValue == "spacenumerr") {
            msg.value = "The question must include one space!";
            setTimeout(() => msg.value = "", 1500);
        } else if (msgValue == "infoerr") {
            msg.value = "Please fill in all the fields!";
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
            <button v-if="/*member == useRoute().params.game || member == 'admin' || !useRoute().params.grade.includes('secondary')*/true" data-bs-toggle="modal" data-bs-target="#overlay" class="btn btn-success">+ add</button>
        </header>
        <main class="d-flex flex-column-reverse pt-2">
            <ControlPanelChoose v-if="['choose', 'biology', 'physics', 'chemistry'].includes(useRoute().params.game)" v-bind="inheritedVariables" />
            <ControlPanelComplete v-else-if="useRoute().params.game == 'complete'" v-bind="inheritedVariables" />
            <ControlPanelRightOrWrong v-else-if="useRoute().params.game == 'right-or-wrong'" v-bind="inheritedVariables" />
            <ControlPanelMatch v-else-if="useRoute().params.game == 'match'" v-bind="inheritedVariables" />
        </main>
</div>
</template>