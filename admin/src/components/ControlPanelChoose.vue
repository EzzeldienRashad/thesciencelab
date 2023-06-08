<script setup>
const props = defineProps(["member", "questions", "addingQuestion", "msg", "deleteQuestion", "addQuestion"]);
const {member, questions, addingQuestion, msg, deleteQuestion, addQuestion} = props;
</script>

<template>
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
    <div v-if="addingQuestion" class="overlay position-fixed top-0 start-0 w-100 h-100 p-2 overflow-scroll">
        <form id="form" method="post" class="bg-light rounded-3 p-2" @submit.prevent="addQuestion">
            <div v-if="msg" class='alert alert-primary text-center h3 p-2 d-flex align-items-center'>{{ msg }}</div>
            <div class="text-end">
                <button class='btn btn-danger btn-close' @click="addingQuestion = !addingQuestion"></button>
            </div>
            <div class="row">
                <label class="form-label">
                    Question: <input type="text" name="question" class="form-control" autocomplete="off" required/>
                </label>
            </div>
            <fieldset class="row">
                <legend>Answers:</legend>
                <label class="form-label col-md-6">
                    First answer: <input type="text" name="first" class="form-control" autocomplete="off" required/>
                </label>
                <label class="form-label col-md-6">
                    Second answer: <input type="text" name="second" class="form-control" autocomplete="off" required/>
                </label>
                <label class="form-label col-md-6">
                    Third answer: <input type="text" name="third" class="form-control" autocomplete="off" required/>
                </label>
                <label class="form-label col-md-6">
                    Fourth answer: <input type="text" name="fourth" class="form-control" autocomplete="off" required/>
                </label>
            </fieldset>
            <label>
                Number of the right answer: <input type="number" name="number" max="4" min="1" autocomplete="off" class="form-control w-50" required/>
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