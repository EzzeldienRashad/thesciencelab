<script setup>
import {ref} from "vue";
import {jsPDF} from "jspdf";
import ScienceFormInput from "@/components/ScienceFormInput.vue";
import symbolsArr from "./symbols.json"

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "member", "uploaders", "routeParams"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, member, uploaders, routeParams} = props;
const form = ref(null);
const symbols = symbolsArr["science"];

defineExpose({exportPdf});

function exportPdf() {
    let questionsText = "";
    for (let j = 0; j < document.querySelectorAll(".question").length; j++) {
        let question = document.querySelectorAll(".question")[j];
        questionsText += (j + 1) +  ") " + question.querySelector(".questionTitle").textContent + "\n";
    }
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.text(30, 30, questionsText);
    pdf.save("questions.pdf");
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