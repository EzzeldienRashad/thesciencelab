<script setup>
import {ref} from "vue";
import {jsPDF} from "jspdf";
import ScienceFormInput from "@/components/ScienceFormInput.vue";
import symbolsArr from "@/assets/info/symbols.json"
import { callAddFont } from "@/assets/fonts/ARIAL-normal";

const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion", "setLevel", "member", "username", "uploaders", "routeParams", "chosenQuestions", "creatingTest"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion, setLevel, member, username, uploaders, routeParams, chosenQuestions, creatingTest} = props;
const form = ref(null);
const symbols = symbolsArr["science"];

jsPDF.API.events.push(["addFonts", callAddFont]);
defineExpose({exportPdf});
defineEmits(["changeChosenQuestions"])

function exportPdf() {
    let pdf = new jsPDF("p", "pt", "A4");
    pdf.setFont("ARIAL", "normal");
    let lines = 1;
    let lineHeight = pdf.getLineHeight();
    let pageHeight = pdf.internal.pageSize.height;
    let questions = document.querySelectorAll(".question");
    for (let j = 0; j < questions.length; j++) {
        let numbered = false;
        for (let questionPart of pdf.splitTextToSize(questions[questions.length - j - 1].querySelectorAll(".questionTitle")[0].textContent + " ....... " + questions[questions.length - j - 1].querySelectorAll(".questionTitle")[1].textContent, 535)) {
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
</script>

<template>
    <div v-for="question in questions" :key="question['id']" @click="$event => {if ($event.target.tagName != 'BUTTON' && $event.target.parentElement.tagName != 'BUTTON' && member == 'admin' && !creatingTest) $event.currentTarget.querySelector('.uploader-name').classList.toggle('d-none');if (creatingTest) $emit('changeChosenQuestions', question['id'])}" class="question border border-2 d-flex flex-column p-2 bg-body-tertiary" :class="{'border-dark': creatingTest, 'chosen': chosenQuestions.includes(question['id'])}">
        <div class="w-100" data-cy="question">
            <span class="questionTitle">{{ question["part1"] }}</span>
            <span class='badge text-bg-success me-1'>{{ question["rightAnswer"] }}</span>
            <span class='badge text-bg-danger me-1'>{{ question["wrongAnswer"] }}</span>
            <span class="questionTitle">{{ question["part2"] }}</span>
            <button v-if="question['uploader'].toLowerCase() == username.toLowerCase() || member == 'admin'" class='btn btn-danger btn-close float-end' @click="deleteQuestion(question['id'])" data-cy="delete-btn"></button>
            <div class="p-1 m-1 rounded-2 bg-body-secondary d-none uploader-name" dir="rtl">{{ uploaders[question["id"]] }}</div>
        </div>
        <div v-if="member == 'admin'" class="d-flex flex-row justify-content-center pt-2 px-1 gap-1" :class="{'bg-success-subtle': question['level'] == 'easy', 'bg-warning-subtle': question['level'] == 'medium', 'bg-danger-subtle': question['level'] == 'hard'}">
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'easy' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-success" @click="() => setLevel('easy', question['id'])"/></button>
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'medium' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-warning" @click="() => setLevel('medium', question['id'])"/></button>
            <button class="right-mark"><font-awesome-icon :icon="[question['level'] == 'hard' ? 'fa-solid' : 'fa-regular', 'fa-circle-check']" class="fa-xl text-danger" @click="() => setLevel('hard', question['id'])"/></button>
        </div>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + (msgColor || 'primary')">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
                    <form ref="form" method="post" @submit.prevent="addQuestion(form)" class="mt-2 row">
                        <ScienceFormInput label="Question: " inputName="question" :symbols/>
                        <ScienceFormInput label="Right answer: " inputName="right" :symbols class="col-12 col-lg-6"/>
                        <ScienceFormInput label="Wrong answer: " inputName="wrong" :symbols class="col-12 col-lg-6"/>
                        <div class="text-center mt-3">
                            <input type="submit" name="submit" value="add" class="btn btn-success col-6" data-cy="submit"/>
                        </div>
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
</style>