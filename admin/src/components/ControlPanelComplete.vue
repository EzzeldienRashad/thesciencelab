<script setup>
const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion} = props;
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
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + msgColor">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
                    <form id="form" method="post" @submit.prevent="addQuestion" class="mt-2">
                        <label class="form-label w-100">
                            Question: <input type="text" name="question" class="form-control" autocomplete="off" required/>
                        </label>
                        <br/>
                        <label class="form-label col-md-6 w-100">
                            answers: <input type="text" name="answers" class="form-control" autocomplete="off" required/>
                        </label>
                        <br/>
                        <br/>
                        <input type="submit" name="submit" value="add" class="btn btn-success"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.overlay {
    z-index: 1000;
    background-color: rgba(0, 0, 0, 0.8);
}
</style>