<script setup>
import {ref} from "vue";
import {jsPDF} from "jspdf";
import { Document, Packer, Paragraph, TextRun, ImageRun } from "docx";
import ScienceFormInput from "@/components/ScienceFormInput.vue";
import symbolsArr from "@/assets/info/symbols.json"
import { callAddFont } from "@/assets/fonts/ARIAL-normal";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "setLevel", "member", "username", "uploaders", "routeParams", "chosenQuestions", "creatingTest"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, setLevel, member, username, uploaders, routeParams, chosenQuestions, creatingTest} = props;
const form = ref(null);
const questionsNum = ref(3);
const symbols = symbolsArr;

jsPDF.API.events.push(["addFonts", callAddFont]);
defineExpose({exportPdf, exportDocx});
defineEmits(["changeChosenQuestions"])

function exportPdf() {
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.setFont("ARIAL", "normal");
    let lines = 1;
    let lineHeight = pdf.getLineHeight();
    let pageHeight = pdf.internal.pageSize.height;
    let questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        let question = questions[questions.length - j - 1];
        pdf.text("Question " + (j + 1) + ":\nColumn A:\n", lineHeight, lineHeight * lines);
        lines += 2;
        for (let colA of shuffle(Array.from(question.querySelectorAll(".colA")))) {
            for (let questionPart of pdf.splitTextToSize(colA.textContent, 535)) {
                if (lines * lineHeight >= pageHeight - lineHeight) {
                    pdf.addPage();
                    lines = 1;
                }
                pdf.text(questionPart, lineHeight, lineHeight * lines);
                lines += 1;
            }
        }
        pdf.text("Column B:\n", lineHeight, lineHeight * lines);
        lines += 1;
        for (let colB of shuffle(Array.from(question.querySelectorAll(".colB")))) {
            for (let questionPart of pdf.splitTextToSize(colB.textContent, 535)) {
                if (lines * lineHeight >= pageHeight - lineHeight) {
                    pdf.addPage();
                    lines = 1;
                }
                pdf.text(questionPart, lineHeight, lineHeight * lines);
                lines += 1;
            }
        }
        pdf.text("----------------------------------------------------------------------------------------\n", lineHeight, lineHeight * lines);
        lines += 1;
    }
    pdf.save(routeParams.grade + "-" + routeParams.game + "-game.pdf");
}
function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}
async function exportDocx() {
    const children = [];
    const questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        children.push(new Paragraph({
            "children": [new TextRun("Question " + (j + 1) + ": ")]
        }));
        children.push(new Paragraph({
            "children": [new TextRun("Column A: ")]
        }));
        const question = questions[questions.length - j - 1];
        for (let colA of shuffle(Array.from(question.querySelectorAll(".colA")))) {
            children.push(new Paragraph({
                "children": [new TextRun(colA.textContent)]
            }));
        }
        children.push(new Paragraph({
            "children": [new TextRun("Column B: ")]
        }));
        for (let colB of shuffle(Array.from(question.querySelectorAll(".colB")))) {
            children.push(new Paragraph({
                "children": [new TextRun(colB.textContent)]
            }));
        }
        children.push(new Paragraph({
            "children": [new TextRun("----------------------------------------------------------------------------------------")]
        }));
    }
    const doc = new Document({
        sections: [{children}],
        styles: {
            default: {
                document: {
                    run: {
                        size: "14pt"
                    }
                }
            }
        }
    });
    Packer.toBlob(doc).then(blob => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = routeParams.grade + "-" + routeParams.game + "-game.docx";
        link.click();
    })
}
</script>

<template>
    <div class="table-responsive my-1" v-for="cols in questions" :key="cols" @click="() => {if (creatingTest) $emit('changeChosenQuestions', cols['id'])}" :class="{'chosen': chosenQuestions.includes(cols['id'])}">
        <tr v-if="((member == routeParams.game || !routeParams.grade.includes('secondary')) && cols['uploader'].toLowerCase() == username.toLowerCase()) || member == 'admin'" class="d-flex flex-column pt-2 px-1 gap-1" :class="{'bg-success-subtle': cols['level'] == 'easy', 'bg-warning-subtle': cols['level'] == 'medium', 'bg-danger-subtle': cols['level'] == 'hard'}">
            <td colspan="2" class="d-flex justify-content-center gap-2">
                <button class="right-mark"><font-awesome-icon :icon="[cols['level'] == 'easy' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-success" @click="() => setLevel('easy', cols['id'])"/></button>
                <button class="right-mark"><font-awesome-icon :icon="[cols['level'] == 'medium' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-warning" @click="() => setLevel('medium', cols['id'])"/></button>
                <button class="right-mark"><font-awesome-icon :icon="[cols['level'] == 'hard' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-danger" @click="() => setLevel('hard', cols['id'])"/></button>
            </td>
        </tr>
        <table class="question w-100 table table-bordered table-striped">
            <thead @click="$event => {if (member == 'admin' && !creatingTest) $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none')}">
                <tr>
                    <th scope="col">Question</th>
                    <th scope="col">Answer</th>
                </tr>
            </thead>
            <tbody @click="$event => $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none')">
                <tr v-for="i in (JSON.parse(cols['colA']) || []).length" :key="i">
                    <td class="colA">{{ JSON.parse(cols["colA"])[i - 1] }}</td>
                    <td class="colB">{{ JSON.parse(cols["colB"])[i - 1] }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="uploader-name d-none">
                    <td colspan="2" class="bg-body-secondary">
                        <div dir="rtl">{{ cols["uploader"] }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center p-0 d-table-cell">
                        <button v-if="cols['uploader'].toLowerCase() == username.toLowerCase() || member == 'admin'" class='btn text-bg-danger btn-close py-2 px-0 w-100' @click="deleteQuestion(cols['id'])" data-cy="delete-btn"></button>
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
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close" data-cy="closeMatch"></button>
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
.right-mark {
    background: none;
	color: inherit;
	border: none;
	padding: 0;
	font: inherit;
	cursor: pointer;
	outline: inherit;
}
</style>