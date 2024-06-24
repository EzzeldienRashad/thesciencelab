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

function exportPdf() {
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.setFont("ARIAL", "normal");
    let lines = 1;
    let lineHeight = pdf.getLineHeight();
    let pageHeight = pdf.internal.pageSize.height;
    let questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        let question = questions[questions.length - j - 1];
        let numbered = false;
        for (let questionPart of pdf.splitTextToSize(question.querySelector(".questionTitle").textContent, 535)) {
            if (lines * lineHeight >= pageHeight - lineHeight) {
                pdf.addPage();
                lines = 1;
            }
            pdf.text((!numbered ? (j + 1) +  ") " : "") + questionPart, lineHeight, lineHeight * lines);
            numbered = true;
            lines += 1;
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
    <div v-for="question in questions" :key="question['id']" 
        @click="$event => {
            if (!$event.target.closest('button') && member == 'admin' && !creatingTest) $event.currentTarget.querySelector('.uploader-name').classList.toggle('d-none'); 
            if (creatingTest) $emit('changeChosenQuestions', question['id']);
        }" class="question p-2 m-1 rounded d-flex flex-column card" 
        :class="[chosenQuestions.includes(question['id']) ?  'chosen' : '']" data-cy="question">
        <div class="pb-2">
            <span class="questionTitle px-1">{{ question['question'] }}</span>
            <span class="border border-2 border-dark fw-bold p-1 rounded-3 m-2">{{ question['answer'] }}</span>
            <button v-if="question['uploader'].toLowerCase() == username.toLowerCase() || member == 'admin'" 
                class='btn btn-danger btn-close float-end' @click="deleteQuestion(question['id'])" data-cy="delete-btn"></button>
        </div>
        <div v-if="member == 'admin'" class="d-flex flex-row p-1 px-1 gap-1 rounded-3 level-indicator justify-content-center" :class="{'bg-success-subtle': question['level'] == 'easy', 'bg-warning-subtle': question['level'] == 'medium', 'bg-danger-subtle': question['level'] == 'hard'}">
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'easy' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-success" @click="() => setLevel('easy', question['id'])"/></button>
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'medium' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-warning" @click="() => setLevel('medium', question['id'])"/></button>
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'hard' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-danger" @click="() => setLevel('hard', question['id'])"/></button>
        </div>
        <div class="p-1 uploader-name d-none" dir="rtl">{{ uploaders[question["id"]] }}</div>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close" data-cy="closeScientific"></button>
                    <form ref="form" type="multipart/form-data" method="post" @submit.prevent="addQuestion(form)" class="mt-2">
                        <ScienceFormInput label="Question: " inputName="question" :symbols/>
                        <br/>
                        <ScienceFormInput label="Scientific Term: " inputName="answer" :symbols/>
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
.right-mark {
    background: none;
	color: inherit;
	border: none;
	padding: 0;
	font: inherit;
	cursor: pointer;
	outline: inherit;
}
.level-indicator {
    background-color: whitesmoke;
}
</style>