<script setup>
const props = defineProps(["questions", "addingQuestion", "msg", "msgColor", "deleteQuestion", "addQuestion"]);
const {questions, addingQuestion, msg, msgColor, deleteQuestion, addQuestion} = props;
</script>

<template>
    <div v-for="(question, index) in questions" :key="question" class="border border-2 d-flex p-2">
        <div class="w-100">
            <template v-for="[key, value] in Object.entries(question)"> 
                {{ key }}
                <template v-if="value.length">
                    <span class='badge text-bg-success me-1'>{{ value[0] }}</span>
                    <span class='badge text-bg-danger me-1'>{{ value[1] }}</span>
                </template> 
            </template>
            <button class='btn btn-danger btn-close float-end' @click="deleteQuestion(index)"></button>
        </div>
    </div>
    <div v-if="addingQuestion" class="overlay position-fixed top-0 start-0 w-100 h-100 p-2 overflow-scroll">
        <form id="form" method="post" class="bg-light rounded-3 p-2" @submit.prevent="addQuestion">
            <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + msgColor">{{ msg }}</div>
            <div class="text-end">
                <button class='btn btn-danger btn-close' @click="addingQuestion = !addingQuestion"></button>
            </div>
            <label class="form-label w-100">
                Question: <input type="text" name="question" class="form-control" autocomplete="off" required/>
            </label>
            <br/>
            <label class="form-label col-md-6 w-100">
                answers: <input type="text" name="answers" class="form-control" autocomplete="off" required/>
            </label>
            <br/>
            <div class="row">
            </div>
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