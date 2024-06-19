<script setup>
import {ref, onMounted} from "vue";
import removeDashes from "../modules/removeDashes";

const member = ref("");
const tests = ref([]);
const currentGame = ref("");
const questions = ref([]);
const date = ref("");

fetch("http://127.0.0.1/info/functions/login.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.text())
    .then(userInfo => {
        try {
            userInfo = JSON.parse(userInfo);
        } catch (e) {
            
        }
        member.value = userInfo[0];
        if (member.value == "admin") {
            fetch("http://127.0.0.1/info/functions/beginTest.php", {
                method: "get",
                credentials: "include"
            })
            .then(res => res.json())
            .then(testsArr => {
                tests.value = testsArr
            })
        }
    });
function showTest(code) {
    currentGame.value = tests.value[code][0]["game"];
    fetch("http://127.0.0.1/info/functions/testQuestions.php?game=" + tests.value[code][0]["game"], {
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
onMounted(() => {
    fetch("http://127.0.0.1/info/functions/getDate.php").then(res => res.text()).then(res => date.value = res);
});
</script>

<template>
    <RouterLink to="/" class="text-dark">
        <font-awesome-icon icon="fa-solid fa-left-long" size="2x" />
    </RouterLink>
    <section v-if="!questions.length" class="row g-4">
        <h2 class="text-primary-emphasis col-12">Running Tests:</h2>
        <div v-for="(test, code) in tests" :key="code" class="col-6 col-sm-4 text-center">
            <button @click="() => showTest(code)" class="btn btn-warning rounded-4 p-3 my-2 position-relative">
                {{ code }}
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
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
                    <img v-if="question['image']" :src="'http://127.0.0.1/info/images/' + question['image']" class="uploaded"/>
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
        <div v-for="question in questions" :key="question['id']" class="question p-3 m-3 rounded d-flex flex-column">
            <span class="questionTitle">{{ question['question'] }}</span>
            <br/>
            <img v-if="question['image']" :src="'http://127.0.0.1/info/images/' + question['image']" class="uploaded"/>
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
</template>