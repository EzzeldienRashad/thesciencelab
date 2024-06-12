<script setup>
import {ref} from "vue";
import {jsPDF} from "jspdf";
import ScienceFormInput from "@/components/ScienceFormInput.vue";
import symbolsArr from "@/assets/info/symbols.json"
import { callAddFont } from "@/assets/fonts/ARIAL-normal";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "member", "uploaders", "routeParams"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, member, uploaders, routeParams} = props;
const form = ref(null);
const symbols = symbolsArr["science"];

jsPDF.API.events.push(["addFonts", callAddFont]);
defineExpose({exportPdf});

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
        let img = question.getElementsByTagName("img")[0];
        if (img) { //fix img type png jpeg .....
                if (lines * lineHeight + Math.ceil(img.clientHeight / img.clientWidth * 300) >= pageHeight - lineHeight) {
                    pdf.addPage();
                    lines = 1;
                }
            pdf.addImage(img.src, 'png', lineHeight, lineHeight * lines, 300, Math.ceil(img.clientHeight / img.clientWidth * 300));
            lines += Math.ceil(img.clientHeight / img.clientWidth * 300 / lineHeight) + 2;
        }
    }
    pdf.save(routeParams.grade + "-" + routeParams.game + "-game.pdf");
}
</script>

<template>
    <div v-for="(questionAnswer, index) in questions" :key="questionAnswer[0]" @click="$event => {if ($event.target.tagName != 'BUTTON' && member == 'admin') $event.currentTarget.querySelector('.uploader-name').classList.toggle('d-none');}" class="question p-3 m-3 rounded" :class="[parseInt(questionAnswer[1]) ? 'text-bg-success' : 'text-bg-danger']" data-cy="question">
        <span class="questionTitle">{{ questionAnswer[0] }}</span>
        <button class='btn btn-danger btn-close float-end' @click="deleteQuestion(index)" data-cy="delete-btn"></button>
        <br/>
        <img v-if="questionAnswer[2]" :src="'http://127.0.0.1/info/images/' + questionAnswer[2]" class="uploaded"/>
        <div class="p-1 uploader-name d-none" dir="rtl">{{ uploaders[index] }}</div>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
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
</style>