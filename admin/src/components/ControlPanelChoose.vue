<script setup>
import {ref, defineExpose} from "vue";
import {jsPDF} from "jspdf";
// import {useRoute} from "vue-router";
import ScienceFormInput from "@/components/ScienceFormInput.vue";
import symbolsArr from "./symbols.json"

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "member", "uploaders", "routeParams"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, member, uploaders, routeParams} = props;
const form = ref(null);
const symbols = symbolsArr[routeParams.game == "choose" ? "science" : routeParams.game];

defineExpose({exportPdf});

function exportPdf() {
    let questionsText = "";
    for (let j = 0; j < document.querySelectorAll(".question").length; j++) {
        let question = document.querySelectorAll(".question")[j];
        questionsText += "\n" + (j + 1) + ") " + question.querySelector(".questionTitle").textContent + "\n";
        for (let i = 0; i < 4; i++) {
            questionsText += ["a) ", "b) ", "c) ", "d) "][i] + question.querySelectorAll(".choice")[i].textContent + "     ";
        }
    }
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.text(30, 30, questionsText);
    pdf.save("questions.pdf");
}
</script>

<template>
    <div v-for="(question, index) in questions" :key="question[0]" class="question card mb-2 border-dark" data-cy="question-cont">
        <div class="questionTitle card-header p-2 fw-bold" @click="$event => {if ($event.target.tagName != 'BUTTON' && member == 'admin') $event.currentTarget.parentElement.querySelector('.uploader-name').classList.toggle('d-none');}" data-cy="question">
            {{ question[0] }}
            <button v-if="/*member == routeParams.game || member == 'admin' || !routeParams.grade.includes('secondary')*/true" class='btn btn-danger btn-close float-end' @click="deleteQuestion(index)" data-cy="delete-btn"></button>
            <br/>
            <img v-if="question[3]" :src="'http://127.0.0.1/info/images/' + question[3]" class="uploaded"/>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div v-for="answer in question[1]" class="col-12 col-sm-6 col-lg-3 border px-1" :class="{'text-bg-success': answer == question[1][question[2] - 1]}">
                    <div class="h-100 choice" data-cy="answer">{{ answer }}</div>
                </div>
            </div>
        </div>
        <div class="card-footer p-1 text-bg-secondary d-none uploader-name" dir="rtl">{{ uploaders[index] }}</div>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
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