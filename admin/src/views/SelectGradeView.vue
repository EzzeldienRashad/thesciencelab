<script setup>
import {ref, inject, onUpdated} from "vue";
import GradeLink from "@/components/GradeLink.vue";
const grades = ref(["grade-4", "grade-5", "grade-6", "1st-prep", "2nd-prep", "3rd-prep", "1st-secondary", "2nd-secondary", "3rd-secondary"]);
const userDetails = ref([]);

const member = inject("member");
fetch("http://127.0.0.1/thesciencelab/info/functions/getMemberInfo.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.json())
    .then(userDetailsArr => userDetails.value = userDetailsArr)
</script>

<template>
<section class="fs-3 row p-3 p-md-5 gy-4">
    <div class="col-12 col-lg-6">
        <div class="card py-2">
            <div class="card-body">
                <h2 dir="rtl" lang="ar" class="card-title">{{ userDetails["name"] }}</h2>
                <hr/>
                <p class="card-text">
                    <span><strong class="fw-bold">username: </strong> {{ userDetails["username"] }}</span><br/>
                    <span><strong class="fw-bold">phone number: </strong> {{ userDetails["phone"] }}</span><br/>
                    <span><strong class="fw-bold">specialization: </strong> {{ userDetails["subject"] }}</span><br/>
                    <span><strong class="fw-bold">school: </strong> {{ userDetails["school"] }}</span><br/>
                    <span><strong class="fw-bold">administration: </strong> {{ userDetails["administration"] }}</span><br/>
                    <span><strong class="fw-bold">code: </strong> {{ userDetails["code"] }}</span><br/>
                    <span><strong class="fw-bold">national ID: </strong> {{ userDetails["nationalId"] }}</span><br/>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <h2 class="col-12">Please choose a grade:</h2>
        <div v-for="grade in grades" :key="grade" class="col-12 p-2">
            <GradeLink :grade="grade"/>
        </div>
    </div>
</section>
</template>