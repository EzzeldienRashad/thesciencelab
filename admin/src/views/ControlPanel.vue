<script setup>
import { ref, nextTick } from "vue";
import { useRouter, useRoute } from "vue-router";
import ControlPanelChoose from "@/components/ControlPanelChoose.vue";
import ControlPanelComplete from "@/components/ControlPanelComplete.vue";
import ControlPanelRightOrWrong from "@/components/ControlPanelRightOrWrong.vue";
import ControlPanelEssay from "@/components/ControlPanelEssay.vue";
import ControlPanelScientificTerm from "@/components/ControlPanelScientificTerm.vue";
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

fetch("http://127.0.0.1/thesciencelab/info/functions/login.php", {
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
    fetch("http://127.0.0.1/thesciencelab/info/functions/printInfo.php?grade=" + encodeURIComponent(routeParams.grade) +
        "&game=" + encodeURIComponent(routeParams.game) + "&unit=" + encodeURIComponent(unit.value))
        .then(res => res.json())
        .then(questionsArr => {
            questions.value = questionsArr.sort((a, b) => (a["uploader"].toLowerCase() == username.value.toLowerCase()) - (b["uploader"].toLowerCase() == username.value.toLowerCase()));
        });
    if (member.value == "admin") {
        fetch("http://127.0.0.1/thesciencelab/info/functions/printUploaders.php?grade=" + routeParams.grade +
            "&game=" + routeParams.game + "&unit=" + unit.value, {
            method: "get",
            credentials: "include"
        })
            .then(res => res.json())
            .then(uploadersArr => uploaders.value = uploadersArr);
    }
}
async function deleteQuestion(questionData) {
    if (!confirm("Are you sure you want to delete this question?")) return;
    fetch("http://127.0.0.1/thesciencelab/info/functions/delete.php?grade=" + routeParams.grade +
        "&game=" + routeParams.game + "&unit=" + unit.value + "&questionnum=" + questionData, {
        method: "get",
        credentials: "include"
    })
        .then(res => res.text())
        .then((msg) => {
            if (msg == "logout") router.push({ name: "login" });
            else loadQuestions();
        });
}
function addQuestion(form) {
    fetch("http://127.0.0.1/thesciencelab/info/functions/add.php?grade=" + routeParams.grade +
        "&game=" + routeParams.game + "&unit=" + unit.value, {
        method: "post",
        credentials: "include",
        body: new FormData(form)
    })
        .then(res => res.text())
        .then(msgValue => {
            form.parentElement.scrollTo(0, 0);
            msgColor.value = "danger";
            switch (msgValue) {
                case "logout":
                    msg.value = "You are not logged in.";
                    setTimeout(() => router.push({ name: "login" }), 1500);
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
    fetch("http://127.0.0.1/thesciencelab/info/functions/setLevel.php?grade=" + routeParams.grade +
        "&game=" + routeParams.game + "&unit=" + unit.value + "&questionnum=" + index + "&level=" + level, {
        method: "get",
        credentials: "include"
    })
        .then(res => res.text())
        .then((msg) => {
            if (msg == "logout") router.push({ name: "login" });
            else loadQuestions();
        });
}
function exportPdf() {
    currentGame.value.exportPdf();
}
function exportDocx() {
    currentGame.value.exportDocx();
}
function beginTest(form) {
    let testForm = new FormData(form);
    testForm.append("chosenQuestions", JSON.stringify(chosenQuestions.value));
    fetch("http://127.0.0.1/thesciencelab/info/functions/beginTest.php?grade=" + routeParams.grade +
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
                    setTimeout(() => router.push({ name: "login" }), 1500);
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
</script>

<template>
    <GradeUnits v-if="!unit" @setUnit="unitValue => { unit = unitValue; loadQuestions() }" />
    <div v-else class="p-2 rounded-3">
        <header class="d-flex justify-content-between align-items-center pb-2 border-bottom border-2">
            <RouterLink to="/" class="text-dark position-absolute top-0 start-0 ms-3 mt-3">
                <font-awesome-icon icon="fa-solid fa-left-long" size="2x" @click="$event => {
                    if (creatingTest) {
                    $event.stopPropagation();
                    $event.preventDefault();
                    unit = '';
                }
            }"/>
            </RouterLink>
            <div class="d-flex flex-wrap-reverse gap-1 ms-2 justify-content-end flex-grow-1">
                <label for="test" v-if="member == 'admin'" data-cy="createTest"
                    class="btn btn-primary border border-primary border-5"
                    :class="[['btn-primary', 'border-primary'], ['btn-danger', 'border-danger']][Number(creatingTest)]">{{
                        ['Create test', 'cancel test'][Number(creatingTest)] }}</label>
                <button v-if="member == 'admin' && chosenQuestions.length && creatingTest"
                    class="btn btn-primary border border-danger border-5" data-bs-toggle="modal"
                    data-bs-target="#testOverlay" data-cy="startTest">Start test!</button>
                <div v-if="!creatingTest" class="ps-2">
                    <button v-if="member == 'admin'" class="btn btn-warning border border-5 border-warning"
                        @click="exportDocx">export docx</button>&nbsp;&nbsp;&nbsp;
                    <button v-if="member == 'admin'" class="btn btn-warning border border-5 border-warning"
                        @click="exportPdf">export pdf</button>
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <div v-if="creatingTest" class="w-100 grow-1">
                        <h2 class="text-center text-primary-emphasis fw-bold test-mode-text">Test Mode</h2>
                        <h3>Please choose the questions:</h3>
                    </div>
                    <button
                        v-if="(member == useRoute().params.game || member == 'admin' || !useRoute().params.grade.includes('secondary')) && !creatingTest"
                        data-bs-toggle="modal" data-bs-target="#overlay" class="btn btn-success" data-cy="add-btn">+add</button>
                </div>
            </div>
        </header>
        <main class="d-flex flex-column-reverse pt-2">
            <input type="checkbox" id="test" class="d-none" v-model="creatingTest"/>
            <ControlPanelChoose v-if="['choose', 'biology', 'physics', 'chemistry'].includes(useRoute().params.game)"
                v-bind="inheritedVariables" ref="currentGame"
                @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)" />
            <ControlPanelComplete v-else-if="useRoute().params.game == 'complete'" v-bind="inheritedVariables"
                ref="currentGame"
                @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)" />
            <ControlPanelRightOrWrong v-else-if="useRoute().params.game == 'right-or-wrong'" v-bind="inheritedVariables"
                ref="currentGame"
                @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)" />
            <ControlPanelEssay
                v-else-if="useRoute().params.game == 'give-reason' || useRoute().params.game == 'what-happens-when'"
                v-bind="inheritedVariables" ref="currentGame"
                @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)" />
            <ControlPanelScientificTerm
                v-else-if="useRoute().params.game == 'scientific-term'"
                v-bind="inheritedVariables" ref="currentGame"
                @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)" />
            <ControlPanelMatch v-else-if="useRoute().params.game == 'match'" v-bind="inheritedVariables"
                ref="currentGame"
                @changeChosenQuestions="id => chosenQuestions.includes(id) ? chosenQuestions = chosenQuestions.filter(item => item != id) : chosenQuestions.push(id)" />
        </main>
    </div>
    <div class="modal" id="testOverlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center'
                        :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"
                        ref="testClose" data-cy="testModalDismiss"></button>
                    <form ref="form" method="post" type="multipart/form-data" @submit.prevent="beginTest(form)"
                        class="mt-2">
                        <label class="w-100">
                            test code: <input type="text" name="testCode" autocomplete="off" max="100" class="form-control"
                                data-cy="testCode" required/>
                        </label>
                        <br />
                        <br />
                        <label class="w-100">
                            The test is valid for <input type="number" name="testDuration" ref="testduration"
                                data-cy="testValidFor" required/> &nbsp; minutes
                        </label>
                        <br />
                        <br />
                        <input type="submit" name="submit" value="Begin test" class="btn btn-success" data-cy="testSubmit"/>
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
    min-width: 280px;
}

input[type="checkbox"]:checked~.question.chosen,
input[type="checkbox"]:checked~.table-responsive.chosen {
    border: 5px solid blue !important;
}
</style>
<style scoped>
.test-mode-text {
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
}
</style>