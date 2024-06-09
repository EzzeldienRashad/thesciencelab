<script setup>
import {ref} from "vue";
import NameCard from "@/components/NameCard.vue";
import GradeLink from "@/components/GradeLink.vue";

const grades = ref(["grade-4", "grade-5", "grade-6", "1st-prep", "2nd-prep", "3rd-prep", "1st-secondary", "2nd-secondary", "3rd-secondary"]);
</script>

<template>
    <section class="p-4 p-sm-5 row gx-0" data-cy="grades">
        <h2 class="col-12">Grades:</h2>
        <div v-for="grade in grades" :key="grade" class="col-12 col-lg-6 p-2">
            <GradeLink :grade="grade"/>
        </div>
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
</template>
<style>
#contributors h1 {
    font-family: "Reem Kufi Fun", sans-serif;
}
#contributors h2 {
    font-family: "Amiri", serif;
    font-weight: 400;
    font-style: normal;
}
</style>