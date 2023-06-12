<script setup>
const props = defineProps(["questions", "addingQuestion", "msg", "msgColor", "deleteQuestion", "addQuestion"]);
const {questions, addingQuestion, msg, msgColor, deleteQuestion, addQuestion} = props;
</script>

<template>
    <div v-for="(answer, question) in questions" :key="question" class="p-3 m-3 rounded" :class="[parseInt(answer) ? 'text-bg-success' : 'text-bg-danger']">
        {{ question }}
        <button class='btn btn-danger btn-close float-end' @click="deleteQuestion(question, true)"></button>
    </div>
    <div v-if="addingQuestion" class="overlay position-fixed top-0 start-0 w-100 h-100 p-2 overflow-scroll">
        <form id="form" method="post" class="bg-light rounded-3 p-2" @submit.prevent="addQuestion">
            <div class="text-end">
                <button class='btn btn-danger btn-close' @click="addingQuestion = !addingQuestion"></button>
            </div>
            <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + msgColor">{{ msg }}</div>
            <div class="row">
                <label class="form-label">
                    Question: <input type="text" name="question" class="form-control" autocomplete="off" required/>
                </label>
            </div>
            <div class="row px-3">
                <div class="col-6 form-check"><input id="right" type="radio" class="form-check-input" name="answer" value="1"/><label for="right" class="form-check-label">right</label></div>
                <div class="col-6 form-check"><input id="wrong" type="radio" class="form-check-input" name="answer" value="0"/><label for="wrong" class="form-check-label">wrong</label></div>
            </div>
            <br/>
            <br/>
            <input type="submit" name="submit" value="submit" class="btn btn-info"/>
        </form>
    </div>
</template>

<style scoped>
.overlay {
    z-index: 1000;
    background-color: rgba(0, 0, 0, 0.8);
}
</style>