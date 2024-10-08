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
const symbols = symbolsArr;

jsPDF.API.events.push(["addFonts", callAddFont]);
defineExpose({exportPdf, exportDocx});
defineEmits(["changeChosenQuestions"])

async function exportPdf() {
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.setFont("ARIAL", "normal");
    let lines = 1;
    let lineHeight = pdf.getLineHeight();
    let pageHeight = pdf.internal.pageSize.height;
    let questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        let question = questions[questions.length - j - 1];
        let numbered = false;
        let choices = "";
        for (let questionPart of pdf.splitTextToSize(question.querySelector(".questionTitle").textContent, 535)) {
            if (lines * lineHeight >= pageHeight - lineHeight) {
                pdf.addPage();
                lines = 1;
            }
            pdf.text((!numbered ? (j + 1) +  ") " : "") + questionPart, lineHeight, lineHeight * lines);
            numbered = true;
            lines += 1;
        }
        for (let i = 0; i < 4; i++) {
            choices += ["a)", "b)", "c)", "d)"][i] + question.querySelectorAll(".choice")[i].textContent + "     ";
        }
        for (let choicesPart of pdf.splitTextToSize(choices, 535)) {
            if (lines * lineHeight >= pageHeight - lineHeight) {
                pdf.addPage();
                lines = 1;
            }
            pdf.text(choicesPart, lineHeight, lineHeight * lines);
            lines += 1;
        }
        let img = question.getElementsByTagName("img")[0];
        if (img) {
            if (lines * lineHeight + Math.ceil(img.clientHeight / img.clientWidth * 300) >= pageHeight - lineHeight) {
                pdf.addPage();
                lines = 1;
            }
            const imageRes = await fetch(img.src);
            const imageBlob = await imageRes.blob();
            pdf.addImage(img.src, imageBlob.type.split("/")[1], lineHeight, lineHeight * lines, 300, Math.ceil(img.clientHeight / img.clientWidth * 300));
            lines += Math.ceil(img.clientHeight / img.clientWidth * 300 / lineHeight) + 2;
        }
    }
    pdf.save(routeParams.grade + "-" + routeParams.game + "-game.pdf");
}
async function exportDocx() {
    const children = [];
    const questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        const question = questions[questions.length - j - 1];
        children.push(new Paragraph({
            "children": [new TextRun((j + 1) + ") " + question.querySelector(".questionTitle").textContent)]
        }));
        let choices = "";
        for (let i = 0; i < 4; i++) {
            choices += ["a)", "b)", "c)", "d)"][i] + question.querySelectorAll(".choice")[i].textContent + "     ";
        }
        children.push(new Paragraph({
            "children": [new TextRun(choices)]
        }));
        const img = question.getElementsByTagName("img")[0];
        if (img) {
            const response = await fetch(img.src);
            const blob = await response.blob();
            const buffer = await readBlobAsArrayBuffer(blob);
            function readBlobAsArrayBuffer(blob) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(new Uint8Array(reader.result));
                    reader.onerror = () => reject(reader.error);
                    reader.readAsArrayBuffer(blob);
                });
            }
            children.push(new Paragraph({
                "children": [new ImageRun({
                    data: buffer,
                    transformation: {
                        width: 400,
                        height: Math.floor(img.clientHeight / img.clientWidth * 400)
                    }
                })]
            }))
        }
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
    <div v-for="question in questions" :key="question['id']" @click="() => {if (creatingTest) $emit('changeChosenQuestions', question['id'])}" class="question card mb-2 border-dark d-flex flex-row" :class="{'chosen': chosenQuestions.includes(question['id'])}" data-cy="question-cont">
        <div v-if="((member == routeParams.game || !routeParams.grade.includes('secondary')) && question['uploader'].toLowerCase() == username.toLowerCase()) || member == 'admin'" class="d-flex flex-column pt-2 pt-lg-0 px-1 gap-1 gap-lg-0" :class="{'bg-success-subtle': question['level'] == 'easy', 'bg-warning-subtle': question['level'] == 'medium', 'bg-danger-subtle': question['level'] == 'hard'}">
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'easy' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-success" @click="() => setLevel('easy', question['id'])"/></button>
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'medium' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-warning" @click="() => setLevel('medium', question['id'])"/></button>
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'hard' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-danger" @click="() => setLevel('hard', question['id'])"/></button>
        </div>
        <div class="flex-grow-1">
            <div class="card-header p-2 fw-bold" @click="$event => {if (!$event.target.closest('button') && member == 'admin' && !creatingTest) $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none');}" data-cy="question">
                <span class="questionTitle">{{ question["question"] }}</span>
                <button v-if="((member == routeParams.game || !routeParams.grade.includes('secondary')) && question['uploader'].toLowerCase() == username.toLowerCase()) || member == 'admin'" class='btn btn-danger btn-close float-end' @click="deleteQuestion(question['id'])" data-cy="delete-btn"></button>
                <br/>
                <img v-if="question['image']" :src="'http://127.0.0.1/thesciencelab/info/images/' + question['image']" class="uploaded"/>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <div v-for="i in [1, 2, 3, 4]" class="col-12 col-sm-6 col-lg-3 border px-1" :class="{'text-bg-success': question['answer'] == i}">
                        <div class="h-100 choice" data-cy="choice">{{ question["choice" + ["A", "B", "C", "D"][i - 1]] }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer p-1 text-bg-secondary d-none uploader-name" dir="rtl">{{ uploaders[question["id"]] }}</div>
        </div>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close" data-cy="closeChoose"></button>
                    <form ref="form" method="post" type="multipart/form-data" @submit.prevent="addQuestion(form)" class="mt-2">
                            <ScienceFormInput label="Question: " inputName="question" :symbols/>
                        <fieldset class="row" data-cy="choices">
                            <legend>Answers:</legend>
                            <ScienceFormInput label="First Choice: " inputName="first" class="col-lg-6" :symbols/>
                            <ScienceFormInput label="Second Choice: " inputName="second" class="col-lg-6" :symbols/>
                            <ScienceFormInput label="Third Choice: " inputName="third" class="col-lg-6" :symbols/>
                            <ScienceFormInput label="Fourth Choice: " inputName="fourth" class="col-lg-6" :symbols/>
                        </fieldset>
                        <label>
                            Number of the right answer: <input type="number" name="number" max="4" min="1" autocomplete="off" class="form-control w-50" required data-cy="right-answer-num"/>
                        </label>
                        <br/>
                        <br/>
                        <label>
                            Upload an image: <input type="file" name="image" data-cy="file-upload"/>
                        </label>
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