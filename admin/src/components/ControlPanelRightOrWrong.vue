<script setup>
import {ref} from "vue";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion} = props;
const form = ref(null);
</script>

<template>
    <div v-for="(answer, question) in questions" :key="question" class="p-3 m-3 rounded" :class="[parseInt(answer) ? 'text-bg-success' : 'text-bg-danger']" data-cy="question">
        {{ question }}
        <button class='btn btn-danger btn-close float-end' @click="deleteQuestion(question, true)" data-cy="delete-btn"></button>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
                    <form ref="form" method="post" @submit.prevent="addQuestion(form)" class="mt-2">
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
                        <input type="submit" name="submit" value="add" class="btn btn-success" data-cy="submit"/>
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