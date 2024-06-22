<script setup>
import {ref, onUpdated} from "vue";
import NameCard from "@/components/NameCard.vue";
import GradeLink from "@/components/GradeLink.vue";
import { RouterLink } from "vue-router";
import UploadedQuestionsView from "./UploadedQuestionsView.vue";
import UploadersView from "./UploadersView.vue";

const grades = ref(["grade-4", "grade-5", "grade-6", "1st-prep", "2nd-prep", "3rd-prep", "1st-secondary", "2nd-secondary", "3rd-secondary"]);
const member = ref("");
const uploadsData = ref(null);
const gradesData = ref(null);
const userDetails = ref([]);

fetch("http://127.0.0.1/thesciencelab/info/functions/login.php", {
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
        if (member.value != "admin") {
            fetch("http://127.0.0.1/thesciencelab/info/functions/getMemberInfo.php", {
                    method: "get",
                    credentials: "include",
                })
                .then(res => res.json())
                .then(userDetailsArr => userDetails.value = userDetailsArr)
        }
    });
onUpdated(() => {
    uploadsData.value.style.height = gradesData.value.offsetHeight + "px";
    if (document.getElementById("uploaders")) document.getElementById("uploaders").style.height = gradesData.value.offsetHeight / 2.1 + "px";
    if (document.getElementById("uploadedQuestions")) document.getElementById("uploadedQuestions").style.height = gradesData.value.offsetHeight / 2.1 + "px";
});
</script>

<template>
<section class="d-flex justify-content-between py-2 mb-2 align-items-center border-bottom mx-5">
    <h1>Welcome to The Science Lab</h1>
    <img src="/favicon.ico" alt="the science lab logo" height="70"/>
</section>
<section class="d-flex">
    <section class="p-1 p-sm-2 row gx-0 col-12 col-xl-5 col-xxl-4 d-inline-block" data-cy="grades">
        <div ref="gradesData">
            <div class="col-12 d-flex mb-2 justify-content-end justify-content-between flex-wrap">
                <h2>Grades:</h2>
                <div class="d-xl-none">
                    <RouterLink to="/uploaders" v-if="member == 'admin'" class="btn btn-warning fw-bold text-decoration-none flex-grow-1 me-1 me-sm-2 admin-btn p-1 p-sm-2 fs-5">uploaders</RouterLink>
                    <RouterLink to="/uploadedQuestions" v-if="member == 'admin'" class="btn btn-warning fw-bold text-decoration-none flex-grow-2 ms-1 ms-sm-1 admin-btn p-1 p-sm-2 fs-5">uploaded questions</RouterLink>
                </div>
            </div>
            <div v-for="grade in grades" :key="grade" class="col-12 p-2">
                <GradeLink :grade="grade"/>
            </div>
            <RouterLink class="btn btn-primary d-block w-100 p-3 fs-4 fw-bold rounded-3" to="/resources">Download Resources</RouterLink>
            <RouterLink v-if="member == 'admin'" class="btn btn-info d-block w-100 p-2 fs-4 fw-bold rounded-3 mt-2" to="/tests">
                Running tests
                <font-awesome-icon class="float-end next-arrow" icon="fa-solid fa-right-long" size="2x"/>
            </RouterLink>
        </div>
    </section>
    <section ref="uploadsData" class="p-1 p-sm-2 col-xl-7 col-xxl-8 d-none d-xl-inline-block overflow-scroll">
        <div v-if="member == 'admin'">
            <UploadersView id="uploaders" class="overflow-y-scroll overflow-x-hidden border p-2 mb-3 shadow bg-light-subtle border-2"/>
            <UploadedQuestionsView id="uploadedQuestions" class="overflow-y-scroll overflow-x-hidden border p-2 mt-3 shadow bg-light-subtle border-2"/>
        </div>
        <div v-else-if="userDetails" class="px-5">
            <h1 dir="rtl" lang="ar" class="text-center p-5">{{ userDetails["name"] }}</h1>
            <span class="display-6"><strong class="fw-bold">username: </strong> {{ userDetails["username"] }}</span>
            <br/>
            <span class="display-6"><strong class="fw-bold">phone number: </strong> {{ userDetails["phone"] }}</span>
            <br/>
            <span class="display-6"><strong class="fw-bold">specialization: </strong> {{ userDetails["subject"] }}</span>
            <br/>
            <span class="display-6"><strong class="fw-bold">school: </strong> {{ userDetails["school"] }}</span>
            <br/>
            <span class="display-6"><strong class="fw-bold">administration: </strong> {{ userDetails["administration"] }}</span>
            <br/>
            <span class="display-6"><strong class="fw-bold">code: </strong> {{ userDetails["code"] }}</span>
            <br/>
            <span class="display-6"><strong class="fw-bold">national ID: </strong> {{ userDetails["nationalId"] }}</span>
            <br/>
        </div>
    </section>
</section>
<section id="contributors" lang="ar" dir="rtl" class="p-4 p-sm-3">
            <h1 class="text-center">منصه بنك الأسئله لمعلمي علوم لغات القليوبيه</h1>
            <div>
                <h2>تحت رعاية:</h2>
                <div class="row g-sm-2">
                    <NameCard title="مديرية التربية والتعليم بالقليوبية" center/>
                    <NameCard title="التوجيه العام لمادة العلوم" center/>
                    <NameCard title="ادارة العبور التعليمية" center/>
                    <NameCard title="مدرسة على بن أبى طالب الرسمية لغات" center/>
                </div>
            </div>
            <br/>
            <div>
                <h2>تحت إشراف ورعاية:</h2>
                <div class="row g-sm-2">
                    <NameCard title="مدير المديرية" description="الأستاذة سماح ابراهيم"/>
                    <NameCard title="وكيل المديرية" description="الأستاذ سعيد ندا"/>
                    <NameCard title="مديرة الشئون التنفيذية" description="الدكتورة فوزية"/>
                    <NameCard title="مدير عام التوجيهات بالمديرية" description="الأستاذ إبراهيم الصغير"/>
                    <NameCard title="توجيه عام للعلوم بالقليوبية" description="الأستاذ علاء شعبان"/>
                    <NameCard title="موجه مركزى" description="الأستاذ محمد السيد مرسي"/>
                    <NameCard title="التوجيه العام لمادة Science" description="الأستاذ أيمن فاضل"/>
                    <NameCard title="توجيه Science" description="الأستاذ صالح محمد"/>
                    <NameCard title="مدير الإدارة" description="الدكتور سعد عسل"/>
                    <NameCard title="وكيل الادارة" description="الأستاذ حسام زهدى"/>
                    <NameCard title="مدير مدرسة على بن أبى طالب الرسمية للغات" description="الأستاذ محمد عبد الحليم السعدنى"/>
                    <NameCard title="أسرة مادة Science بالمدرسة" description="الأستاذة أمل خالد والأستاذة غادة سعيد"/>
                    <NameCard title="مصمم الموقع" description="الطالب عزالدين رشاد محمد حسن"/>
                    <NameCard description="مدرسة ستيم العبور" center/>
                    <NameCard title="مشرف المادة بالمدرسة" description="الأستاذ طارق على سليمان"/>
                </div>
            </div>
        </section>
</template>
<style scoped>
#contributors h1 {
    font-family: "Reem Kufi Fun", sans-serif;
}
#contributors h2 {
    font-family: "Amiri", serif;
    font-weight: 400;
    font-style: normal;
}
.admin-btn {
    max-width: 300px;
}
</style>