<script setup>
import {ref} from "vue";
import {jsPDF} from "jspdf";
import ScienceFormInput from "@/components/ScienceFormInput.vue";
import symbolsArr from "@/assets/info/symbols.json"
import { callAddFont } from "@/assets/fonts/ARIAL-normal";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "member", "uploaders", "routeParams"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, member, uploaders, routeParams} = props;
const form = ref(null);
const questionsNum = ref(3);
const symbols = symbolsArr["science"];

jsPDF.API.events.push(["addFonts", callAddFont]);
defineExpose({exportPdf});

function exportPdf() {
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.setFont("ARIAL", "normal");
    let questionsText = "";
    let questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        let question = questions[questions.length - j - 1];
        questionsText += "Question " + (j + 1) + ":\nColumn A:\n";
        for (let colA of shuffle(Array.from(question.querySelectorAll(".colA")))) {
            questionsText += pdf.splitTextToSize(colA.textContent, 500).join("\n") + "\n";
        }
        questionsText += "Column B:\n";
        for (let colB of shuffle(Array.from(question.querySelectorAll(".colB")))) {
            questionsText += pdf.splitTextToSize(colB.textContent, 500).join("\n") + "\n";
        }
        questionsText += "--------------------------------\n";
    }
    pdf.text(questionsText, 30, 30);
    pdf.save(routeParams.grade + "-" + routeParams.game + "-game.pdf");
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
            <thead @click="$event => {if (member == 'admin') $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none')}">
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
                        <button class='btn text-bg-danger btn-close py-2 px-0 w-100' @click="deleteQuestion(index)" data-cy="delete-btn"></button>
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
                            <ScienceFormInput label="Question: " inputName="questions[]" :symbols/>
                            <ScienceFormInput label="Answer: " inputName="answers[]" :symbols/>
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