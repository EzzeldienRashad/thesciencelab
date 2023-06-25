<script setup>
import {ref} from "vue";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion} = props;
const questionsNum = ref(3);
</script>

<template>
    <div class="table-responsive" v-for="(questionGroup, index) in questions" :key="questionGroup">
        <table class="w-100 table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">Question</th>
                    <th scope="col">Answer</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(answer, question) in questionGroup" :key="question">
                    <td>{{ question }}</td>
                    <td>{{ answer }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center p-0 d-table-cell">
                        <button class='btn text-bg-danger btn-close py-2 px-0 w-100' @click="deleteQuestion(index)">
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + msgColor">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
                    <form id="form" method="post" @submit.prevent="addQuestion" class="mt-2">
                        <template v-for="n in questionsNum">
                            <label class="form-label w-100">
                                Question: <input type="text" name="questions[]" class="form-control" autocomplete="off" required/>
                            </label>
                            <label class="form-label w-100">
                                answer: <input type="text" name="answers[]" class="form-control" autocomplete="off" required/>
                            </label>
                            <hr/>
                        </template>
                        <button class="btn btn-info float-end ms-2" @click="questionsNum++">+ question</button>
                        <button class="btn btn-danger float-end" @click="questionsNum--">- question</button>
                        <input type="submit" name="submit" value="add" class="btn btn-success"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
table {
    table-layout: fixed;
}
.overlay {
    z-index: 1000;
    background-color: rgba(0, 0, 0, 0.8);
}
</style>