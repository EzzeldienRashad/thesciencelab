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
const username = ref("");
const questions = ref([]);
const msg = ref("");
const msgColor = ref("");
const creatingTest = ref(false);
const form = ref(null);
const testClose = ref(null);
const chosenQuestions = ref([]);
const inheritedVariables = {
    questions,
    msg,
    msgColor,
    deleteQuestion,
    addQuestion,
    setLevel,
    member,
    username,
    uploaders,
    routeParams,
    chosenQuestions,
    creatingTest
}
const currentGame = ref(null);

fetch("http://127.0.0.1/info/functions/login.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.text())
    .then(userInfo => {
        try {
            userInfo = JSON.parse(userInfo)
        } catch (e) {

        }
        member.value = userInfo[0];
        username.value = userInfo[1];
    });

function loadQuestions() {
    fetch(encodeURI("http://127.0.0.1/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value))
    .then(res => res.json())
    .then(questionsArr => questions.value = questionsArr.sort((a, b) => (a["uploader"].toLowerCase() == username.value.toLowerCase()) - (b["uploader"].toLowerCase() == username.value.toLowerCase())));
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
        switch (msgValue) {
            case "logout":
                msg.value = "You are not logged in.";
                setTimeout(() => router.push({name: "login"}), 1500);
                break;
            case "spacenumerr":
                msg.value = "The question must include one space!";
                setTimeout(() => msg.value = "", 1500);
                break;
            case "infoerr":
                msg.value = "Please fill in all the fields!";
                setTimeout(() => msg.value = "", 1500);
                break;
            case "big":
                msg.value = "The image uploaded is too big!";
                setTimeout(() => msg.value = "", 1500);
                break;
            case "typeerr":
                msg.value = "Only images are allowed!";
                setTimeout(() => msg.value = "", 1500);
                break;
            case "successful":
                msgColor.value = "success"
                form.reset();
                msg.value = "Added successfully!";
                setTimeout(() => msg.value = "", 1000);
                loadQuestions();
                break;
            default:
                msg.value = "An error occured.";
                setTimeout(() => msg.value = "", 1500);            
        }
    });
}
function setLevel(level, index) {
    fetch("http://127.0.0.1/info/functions/setLevel.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value + "&questionnum=" + index + "&level=" + level, {
        method: "get",
        credentials: "include"
    })
    .then(res => res.text())
    .then((msg) => {
        if (msg == "logout") router.push({name: "login"});
        else loadQuestions();
    });
}
function exportPdf() {
    currentGame.value.exportPdf();
}
function beginTest(form) {
    let testForm = new FormData(form);
    testForm.append("chosenQuestions", JSON.stringify(chosenQuestions.value));
    fetch("http://127.0.0.1/info/functions/beginTest.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game, {
        method: "post",
        credentials: "include",
        body: testForm
    })
    .then(res => res.text())
    .then(msgValue => {
        form.parentElement.scrollTo(0, 0);
        msgColor.value = "danger";
        switch (msgValue) {
            case "logout":
                msg.value = "You are not logged in.";
                setTimeout(() => router.push({name: "login"}), 1500);
                break;
            case "infoerr":
                msg.value = "Please fill in all the fields!";
                setTimeout(() => msg.value = "", 1500);
                break;
            case "successful":
                msgColor.value = "success"
                form.reset();
                msg.value = "The test has begun!";
                setTimeout(() => {
                    msg.value = "";
                    testClose.value.click();
                    creatingTest.value = false;
                    chosenQuestions.value = [];
                }, 1000);
                break;
            default:
                msg.value = "An error occured.";
                setTimeout(() => msg.value = "", 1500);            
        }
    });
}
function exportWord() {
    //todo
}
</script>

<template>
    <GradeUnits v-if="!unit" @setUnit="unitValue => {unit = unitValue; loadQuestions() }" />
    <div v-else :class="{'bg-primary-subtle': creatingTest}" class="p-2 rounded-3">
        <header class="d-flex justify-content-between align-items-center pb-2 border-bottom border-2">
            <RouterLink to="/" class="text-dark">
                <font-awesome-icon icon="fa-solid fa-left-long" size="2x" />
            </RouterLink>
            <div class="d-flex flex-wrap-reverse gap-1 ms-2 justify-content-end flex-grow-1">
                <label for="test" v-if="member == 'admin'" @click="creatingTest = !creatingTest" class="btn btn-primary border border-primary border-5" :class="[['btn-primary', 'border-primary'], ['btn-danger', 'border-danger']][Number(creatingTest)]">{{ ['Create test', 'cancel test'][Number(creatingTest)] }}</label>&nbsp;&nbsp;&nbsp;
                <button v-if="member == 'admin' && chosenQuestions.length" class="btn btn-primary border border-danger border-5" data-bs-toggle="testModal" data-bs-target="#testOverlay">Start test!</button>&nbsp;&nbsp;&nbsp;
                <div>
                    <button v-if="member == 'admin'" class="btn btn-warning border border-5 border-warning" @click="exportDocx">export docx</button>&nbsp;&nbsp;&nbsp;
                    <button v-if="member == 'admin'" class="btn btn-warning border border-5 border-warning" @click="exportPdf">export pdf</button>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <h4 v-if="creatingTest" class="flex-grow-1 text-center text-primary-emphasis fs-3 fw-bold">Test Mode</h4>
                    <button v-if="member == useRoute().params.game || member == 'admin' || !useRoute().params.grade.includes('secondary')" data-bs-toggle="modal" data-bs-target="#overlay" class="btn btn-success" data-cy="add-btn">+ add</button>
                </div>
            </div>
        </header>
        <main class="d-flex flex-column-reverse pt-2">
            <input type="checkbox" id="test" class="d-none"/>
            <ControlPanelChoose v-if="['choose', 'biology', 'physics', 'chemistry'].includes(useRoute().params.game)" v-bind="inheritedVariables" ref="currentGame" @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)"/>
            <ControlPanelComplete v-else-if="useRoute().params.game == 'complete'" v-bind="inheritedVariables" ref="currentGame" @changeChosenQuestions="newChosenQuestions => chosenQuestions = newChosenQuestions"/>
            <ControlPanelRightOrWrong v-else-if="useRoute().params.game == 'right-or-wrong'" v-bind="inheritedVariables" ref="currentGame" @changeChosenQuestions="newChosenQuestions => chosenQuestions = newChosenQuestions"/>
            <ControlPanelMatch v-else-if="useRoute().params.game == 'match'" v-bind="inheritedVariables" ref="currentGame" @changeChosenQuestions="newChosenQuestions => chosenQuestions = newChosenQuestions"/>
        </main>
    </div>
    <div class="testModal" id="testOverlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="testModal" aria-label="close"></button>
                    <form ref="form" method="post" type="multipart/form-data" @submit.prevent="addQuestion(form)" class="mt-2">
                        <label>
                            Number of the right answer: <input type="text" name="testCode" autocomplete="off" class="form-control w-50" required/>
                        </label>
                        <br/>
                        <br/>
                        <label class="w-100">
                            The test is valid for <input type="number" name="testDuration" ref="testduration" required/> &nbsp; minutes
                        </label>
                        <br/>
                        <br/>
                        <input type="submit" name="submit" value="Begin test" class="btn btn-success"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .question img {
        max-width: 100%;
        max-height: 500px;
        min-width: 300px;
    }
    input[type="checkbox"]:checked ~ .question.chosen {
        border: 5px solid blue !important;
    }
    input[type="checkbox"]:checked ~ .question.chosen .uploader-name {
        display: none !important;
    }
</style>