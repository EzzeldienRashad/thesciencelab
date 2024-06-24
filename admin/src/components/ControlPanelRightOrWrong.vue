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
        for (let questionPart of pdf.splitTextToSize(question.querySelector(".questionTitle").textContent, 535)) {
            if (lines * lineHeight >= pageHeight - lineHeight) {
                pdf.addPage();
                lines = 1;
            }
            pdf.text((!numbered ? (j + 1) +  ") " : "") + questionPart, lineHeight, lineHeight * lines);
            numbered = true;
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
    <div v-for="question in questions" :key="question['id']" @click="$event => {if (!$event.target.closest('button') && member == 'admin' && !creatingTest) $event.currentTarget.querySelector('.uploader-name').classList.toggle('d-none'); if (creatingTest) $emit('changeChosenQuestions', question['id']);}" class="question p-3 m-3 rounded d-flex flex-column" :class="[parseInt(question['answer']) ? 'text-bg-success' : 'text-bg-danger', chosenQuestions.includes(question['id']) ?  'chosen' : '']" data-cy="question">
        <div>
            <span class="questionTitle">{{ question['question'] }}</span>
            <button v-if="question['uploader'].toLowerCase() == username.toLowerCase() || member == 'admin'" class='btn btn-danger btn-close float-end' @click="deleteQuestion(question['id'])" data-cy="delete-btn"></button>
            <br/>
            <img v-if="question['image']" :src="'http://127.0.0.1/thesciencelab/info/images/' + question['image']" class="uploaded"/>
        </div>
        <div v-if="member == 'admin'" class="d-flex flex-row p-1 px-1 gap-1 rounded-3 level-indicator" :class="{'bg-success-subtle': question['level'] == 'easy', 'bg-warning-subtle': question['level'] == 'medium', 'bg-danger-subtle': question['level'] == 'hard'}">
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
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close" data-cy="closeRightOrWrong"></button>
                    <form ref="form" type="multipart/form-data" method="post" @submit.prevent="addQuestion(form)" class="mt-2">
                        <ScienceFormInput label="Question: " inputName="question" :symbols/>
                        <div class="row px-3">
                            <div class="col-6 form-check"><input id="right" type="radio" class="form-check-input" name="answer" value="1"/><label for="right" class="form-check-label">right</label></div>
                            <div class="col-6 form-check"><input id="wrong" type="radio" class="form-check-input" name="answer" value="0"/><label for="wrong" class="form-check-label">wrong</label></div>
                        </div>
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