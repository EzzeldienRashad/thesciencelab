<script setup>
import {ref, inject} from "vue";
import BouncingBallsDecoration from "@/components/BouncingBallsDecoration.vue";
import GradeLink from "@/components/GradeLink.vue";
import NameCard from "@/components/NameCard.vue";
import logo from "@/assets/icons/logo.webp";

const grades = ref([
    "grade-4", "grade-5", "grade-6", "1st-prep", "2nd-prep", "3rd-prep", "1st-secondary", "2nd-secondary", "3rd-secondary",
]);
const documentWidth = inject("documentWidth");
</script>

<template>
    <div>
        <section class="position-relative">
            <BouncingBallsDecoration />
            <div class="overlay overflow-hidden position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-around align-items-center">
                <h1 class="display-1 m-0 text-center fw-bold">
                    The<br/>
                    Science<br/>
                    Lab<br/>
                </h1>
                <img v-if="documentWidth > 500" :src="logo" alt="The Science Lab" width="230" height="230"/>
            </div>
        </section>
        <section class="p-4 p-sm-5">
            <h2 class="text-center">Welcome to the science lab!</h2>
            <p :class="['p-lg-3', documentWidth > 800 ? 'fs-5' : '']">
                Welcome to The Science Lab! The best place for learning Science!
                Here, you will find different collections of games for learning and practicing different Science lessons. 
                We offer a variety of games developed especially for Egyptian students to help them understand their lessons in an easier and funnier way. 
                We help you memorize the information more effectively. 
                We always try our best to improve our content and offer the best experience for our users. 
                We hope you a fun and intersting journey here in The Science Lab! 
            </p>
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
                    <NameCard title="مدير المديرية" description="الدكتورة سماح ابراهيم"/>
                    <NameCard title="وكيل المديرية" description="الأستاذ سعيد ندا"/>
                    <NameCard title="مديرة الشئون التربوية" description="الدكتورة سماح ابراهيم"/>
                    <NameCard title="مدير عام التوجيهات بالمديرية" description="الأستاذ إبراهيم الصغير"/>
                    <NameCard title="توجيه عام للعلوم بالقليوبية" description="الأستاذ علاء شعبان"/>
                    <NameCard title="موجه مركزى" description="الأستاذ محمد السيد مرسي"/>
                    <NameCard title="التوجيه العام لمادة Science" description="الأستاذ أيمن فاضل"/>
                    <NameCard title="توجيه Science" description="الأستاذ صالح محمد"/>
                    <NameCard title="مدير الإدارة" description="الدكتور سعد عسل"/>
                    <NameCard title="وكيل الادارة" description="الأستاذ حسام زهدى"/>
                    <NameCard title="مدير مدرسة على بن أبى طالب الرسمية للغات" description="الأستاذ محمد عبد الحليم السعدنى"/>
                    <NameCard description="أسرة مادة Science بالمدرسة" center/>
                    <NameCard title="مصمم الموقع" description="الطالب عزالدين رشاد"/>
                    <NameCard description="مدرسة ستيم العبور" center/>
                    <NameCard title="مشرف المادة بالمدرسة" description="الأستاذ طارق على سليمان"/>
                </div>
            </div>
        </section>
        <section class="p-4 p-sm-5 row gx-0" data-cy="grades">
            <h2 class="col-12">Grades:</h2>
            <div v-for="grade in grades" :key="grade" class="col-12 col-lg-6 p-2">
                <GradeLink :grade="grade"/>
            </div>
        </section>
    </div>
</template>

<style>
.overlay {
    background-color: rgba(0, 0, 0, 0.1);   
}
.overlay h1 {
    font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    color: transparent;
    background-clip: text;
    -webkit-background-clip: text;
    background-image: linear-gradient(to top, rgb(255, 0, 0), rgb(0, 255, 0) 33%, rgb(0, 0, 255) 66%, rgb(225, 0, 0) 100%);
    background-size: 100% 200%;
    animation-name: move-gradient;
    animation-duration: 5s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}
@keyframes move-gradient {
    to {
        background-position: 0% 200%;
    }
}
.bullet-arrow {
    list-style-type: "- ";
}
#contributors h1 {
    font-family: "Reem Kufi Fun", sans-serif;
}
#contributors h2 {
    font-family: "Amiri", serif;
    font-weight: 400;
    font-style: normal;
}
</style>