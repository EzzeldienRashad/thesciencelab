<script setup>
import {ref, toRef} from "vue";
import { useRoute } from "vue-router";

const member = ref("");
const form = ref(null);
const routeParams = useRoute().params;
const props = defineProps({unit: String});
const unit = toRef(props, "unit");
const questions = ref([]);
const addingQuestion = ref(false);
const msg = ref("");

loadQuestions();
fetch("http://127.0.0.1/TheScienceLab/info/functions/login.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.text())
    .then(memberValue => member.value = memberValue);

function loadQuestions() {
    fetch(encodeURI("http://127.0.0.1/TheScienceLab/info/functions/printInfo.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value))
    .then(res => res.json())
    .then(questionsArr => questions.value = questionsArr);
}

function deleteQuestion(questionNum) {
    fetch("http://127.0.0.1/TheScienceLab/info/functions/delete.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value + "&questionnum=" + questionNum, {
        method: "get",
        credentials: "include"
    })
    .then(() => {
        loadQuestions();
    });
}

function addQuestion() {
    fetch("http://127.0.0.1/TheScienceLab/info/functions/add.php?grade=" + routeParams.grade +
    "&game=" + routeParams.game + "&unit=" + unit.value, {
        method: "post",
        credentials: "include",
        body: new FormData(form.value)
    })
    .then(res => res.text())
    .then(msgValue => {
        msg.value = msgValue;
        setTimeout(() => msg.value = "", 1000);
        loadQuestions();
    });
}
</script>

<template>
    <header class="d-flex justify-content-between align-items-center pb-2 border-bottom border-2">
        <RouterLink to="/" class="text-dark">
            <font-awesome-icon icon="fa-solid fa-left-long" size="2x" />
        </RouterLink>
        <button @click="addingQuestion = !addingQuestion" class="btn btn-success">+ add</button>
    </header>
    <main class="d-flex flex-column-reverse pt-2">
        <div v-for="(question, index) in questions" :key="question[0]" class="card mb-2 border-dark">
            <div class="card-header p-2 fw-bold">
                {{ question[0] }}
                <button v-if="member == 'admin'" class='btn btn-danger btn-close float-end' @click="deleteQuestion(index)"></button>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <div v-for="answer in question[1]" class="col-12 col-sm-6 col-lg-3 border px-1" :class="{'text-bg-success': answer == question[1][question[2] - 1]}">
                        <div class="h-100">{{ answer }}</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div v-if="addingQuestion" class="overlay position-fixed top-0 start-0 w-100 h-100 p-2 overflow-scroll">
        <form ref="form" method="post" class="bg-light rounded-3 p-2" @submit.prevent="addQuestion">
            <div v-if="msg" class='alert alert-success text-center h3 p-2 d-flex align-items-center'>{{ msg }}</div>
            <div class="text-end">
                <button class='btn btn-danger btn-close' @click="addingQuestion = !addingQuestion"></button>
            </div>
            <div class="row">
                <label class="form-label">
                    Question: <input type="text" name="question" class="form-control" required/>
                </label>
            </div>
            <fieldset class="row">
                <legend>Answers:</legend>
                <label class="form-label col-md-6">
                    First answer: <input type="text" name="first" class="form-control" required/>
                </label>
                <label class="form-label col-md-6">
                    Second answer: <input type="text" name="second" class="form-control" required/>
                </label>
                <label class="form-label col-md-6">
                    Third answer: <input type="text" name="third" class="form-control" required/>
                </label>
                <label class="form-label col-md-6">
                    Fourth answer: <input type="text" name="fourth" class="form-control" required/>
                </label>
            </fieldset>
            <label>
                Number of the right answer: <input type="number" name="number" max="4" min="1" class="form-control w-50" required/>
            </label>
            <br/>
            <br/>
            <input type="submit" name="submit" value="add" class="btn btn-info"/>
        </form>

    </div>
</template>

<style scoped>
.overlay {
    z-index: 1000;
    background-color: rgba(0, 0, 0, 0.8);
}
</style>