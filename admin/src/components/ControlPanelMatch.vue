<script setup>
import {ref} from "vue";
import {useRoute} from "vue-router";
import {jsPDF} from "jspdf";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "member", "uploaders"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, member, uploaders} = props;
const form = ref(null);
const questionsNum = ref(3);

defineExpose({exportPdf});

function exportPdf() {
    let questionsText = "";
    for (let j = 0; j < document.querySelectorAll(".question").length; j++) {
        let question = document.querySelectorAll(".question")[j];
        questionsText += "Question 1:\nColumn A:\n";
        for (let colA of shuffle(Array.from(question.querySelectorAll(".colA")))) {
            questionsText += colA.textContent + "\n";
        }
        questionsText += "Column B:\n";
        for (let colB of shuffle(Array.from(question.querySelectorAll(".colB")))) {
            questionsText += colB.textContent + "\n";
        }
        questionsText += "--------------------------------\n";
    }
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.text(30, 30, questionsText);
    pdf.save("questions.pdf");
}
function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}
</script>

<template>
    <div class="table-responsive" v-for="(questionGroup, index) in questions" :key="questionGroup">
        <table class="question w-100 table table-bordered table-striped">
            <thead @click="$event => $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none')">
                <tr>
                    <th scope="col">Question</th>
                    <th scope="col">Answer</th>
                </tr>
            </thead>
            <tbody @click="$event => $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none')">
                <tr v-for="(answer, question) in questionGroup" :key="question">
                    <td class="colA">{{ question }}</td>
                    <td class="colB">{{ answer }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="uploader-name d-none">
                    <td colspan="2" class="bg-body-secondary">
                        <div dir="rtl">{{ uploaders[index] }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center p-0 d-table-cell">
                        <button v-if="member == useRoute().params.game || member == 'admin' || !useRoute().params.grade.includes('secondary')" class='btn text-bg-danger btn-close py-2 px-0 w-100' @click="deleteQuestion(index)" data-cy="delete-btn"></button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
                    <form ref="form" method="post" @submit.prevent="addQuestion(form)" class="mt-2">
                        <template v-for="n in questionsNum">
                            <label class="form-label w-100">
                                Question: <input type="text" name="questions[]" class="form-control" autocomplete="off" required/>
                            </label>
                            <label class="form-label w-100">
                                answer: <input type="text" name="answers[]" class="form-control" autocomplete="off" required/>
                            </label>
                            <hr/>
                        </template>
                        <button class="btn btn-info float-end ms-2" @click.prevent="questionsNum++">+ question</button>
                        <button class="btn btn-danger float-end" @click.prevent="questionsNum--">- question</button>
                        <input type="submit" name="submit" value="add" class="btn btn-success" data-cy="submit"/>
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