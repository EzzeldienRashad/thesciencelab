<script setup>
import {ref, inject, onMounted} from "vue";
import removeDashes from "../modules/removeDashes";
import { onBeforeRouteLeave } from "vue-router";

const member = inject("member");
const tests = ref([]);
const currentGame = ref("");
const questions = ref([]);
const devicesInTest = ref([]);
const date = ref("");
let fetchDevicesInterval;

getTests()

function getTests() {
    if (member.value == "admin") {
        fetch("http://127.0.0.1/thesciencelab/info/functions/beginTest.php", {
            method: "get",
            credentials: "include"
        })
        .then(res => res.json())
        .then(testsArr => {
            tests.value = testsArr
        })
    }
}
function showTest(code) {
    currentGame.value = tests.value[code][0]["game"];
    function fetchDevicesInTest() {
        fetch("http://127.0.0.1/thesciencelab/info/functions/showDevicesInTest.php?game=" + encodeURIComponent(currentGame.value) +
            "&grade=" + encodeURIComponent(tests.value[code][0]["grade"]) +
            "&code=" + encodeURIComponent(code))
            .then(res => res.json())
            .then(devicesArr => devicesInTest.value = devicesArr);
    }
    fetchDevicesInTest();
    fetchDevicesInterval = setInterval(fetchDevicesInTest, 5000);
    fetch("http://127.0.0.1/thesciencelab/info/functions/testQuestions.php?game=" + tests.value[code][0]["game"], {
        method: "post",
        headers: {
            "content-type": "multipart/form-data"
        },
        body: JSON.stringify({
            "ids": tests.value[code].map(arr => arr["questionId"])
        })
    })
        .then(res => res.json())
        .then(questionsArr => {
            questions.value = questionsArr;
        })
}
function deleteTest(code) {
    if (!confirm("Are you sure you want to delete this test?")) return;
    fetch("http://127.0.0.1/thesciencelab/info/functions/deleteTest.php?code=" + encodeURIComponent(code), {
        method: "get",
        credentials: "include"
    })
        .then(res => res.text())
        .then(msg => {
            if (msg == "successful") {
                getTests()
            }
        })
}
onMounted(() => {
    fetch("http://127.0.0.1/thesciencelab/info/functions/getDate.php").then(res => res.text()).then(res => date.value = res);
});
onBeforeRouteLeave(() => {
    clearInterval(fetchDevicesInterval)
});
</script>

<template>
<RouterLink to="/" class="text-dark">
    <font-awesome-icon icon="fa-solid fa-left-long" size="2x" />
</RouterLink>
<section v-if="questions.length" class="mt-1 mb-3">
    <div class="accordion" id="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fs-4" type="button" data-bs-toggle="collapse" data-bs-target="#studentsInTest" aria-expanded="false" aria-controls="studentsInTest">
                    Show students in test
                </button>
            </h2>
            <div id="studentsInTest" class="accordion-collapse collapse" data-bs-parent="#accordion">
                <div class="accordion-body d-flex flex-column">
                    <div v-for="deviceInTest in devicesInTest">
                        <span class="fs-5">{{ deviceInTest["name"] }}</span>&nbsp;&nbsp;
                        <span class="rounded-circle d-inline-block" :class="[deviceInTest['status'] == 'completed' ? 'bg-success' : 'bg-danger']" style="width: 15px; height: 15px;"></span>
                        <span class="float-end fs-2" v-if="deviceInTest['status'] == 'completed'">{{ deviceInTest["result"] }}%</span>
                        <hr/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section v-if="!questions.length" class="row g-4">
    <h2 class="text-primary-emphasis col-12">Running Tests:</h2>
    <div v-for="(test, code) in tests" :key="code" class="col-6 col-sm-4 text-center" :data-cy="code">
        <button @click="() => showTest(code)" class="btn btn-warning rounded-4 p-3 my-2 position-relative">
            {{ code }}
            <span @click.stop="() => deleteTest(code)" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" data-cy="deleteTest">
                <button class="btn-close fs-5"></button>
                <span class="visually-hidden">Close</span>
            </span>
        </button>
        <br/>
        {{ removeDashes(test[0]["grade"]) }}
        <br/>
        {{ test[0]["game"].replace("Questions", " game") }}
        <br/>
        valid for {{ Math.ceil((new Date(test[0]["validFor"]).getTime() - new Date(date).getTime()) / 1000 / 60) }} minutes
    </div>
</section>
<section v-else-if="currentGame == 'ChooseQuestions'">
    <div v-for="question in questions" :key="question['id']" class="card mb-2 border-dark d-flex flex-row">
        <div class="flex-grow-1">
            <div class="card-header p-2 fw-bold">
                <span class="questionTitle">{{ question["question"] }}</span>
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
        </div>
    </div>
</section>
<section v-else-if="currentGame = 'RightOrWrongQuestions'">
    <div v-for="question in questions" :key="question['id']" class="question p-3 m-3 rounded d-flex flex-column" :class="[parseInt(question['answer']) ? 'text-bg-success' : 'text-bg-danger']">
        <span class="questionTitle">{{ question['question'] }}</span>
        <br/>
        <img v-if="question['image']" :src="'http://127.0.0.1/thesciencelab/info/images/' + question['image']" class="uploaded"/>
    </div>
</section>
<section v-else-if="currentGame = 'CompleteQuestions'">
    <div v-for="question in questions" :key="question['id']" class="question border border-2 d-flex flex-column p-2 bg-body-tertiary w-100">
        <span class="questionTitle">{{ question["part1"] }}</span>
        <span class='badge text-bg-success me-1 right-answer'>{{ question["rightAnswer"] }}</span>
        <span class='badge text-bg-danger me-1 wrong-answer'>{{ question["wrongAnswer"] }}</span>
        <span class="questionTitle">{{ question["part2"] }}</span>
    </div>
</section>
<section v-else-if="currentGame = 'MatchQuestions'">
    <div class="table-responsive my-1" v-for="cols in questions" :key="cols">
        <table class="question w-100 table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">Question</th>
                    <th scope="col">Answer</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in (JSON.parse(cols['colA']) || []).length" :key="i">
                    <td class="colA">{{ JSON.parse(cols["colA"])[i - 1] }}</td>
                    <td class="colB">{{ JSON.parse(cols["colB"])[i - 1] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<section v-else-if="currentGame = 'ScientificTermQuestions'">
        <div v-for="question in questions" :key="question['id']" class="question p-3 m-3 rounded d-flex flex-column">
            <span class="questionTitle">{{ question['question'] }}</span>
            <span class="border border-2 border-dark fw-bold p-1 rounded-3 m-2">{{ question['answer'] }}</span>
        </div>
    </section>
</template>