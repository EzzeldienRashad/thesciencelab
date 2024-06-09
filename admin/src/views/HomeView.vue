<script setup>
import {ref} from "vue";
import removeDashes from "@/modules/removeDashes.js"
import NameCard from "@/components/NameCard.vue";

const grades = ref([
    "grade-4-first-term", "grade-4-second-term",
    "grade-5-first-term", "grade-5-second-term",
    "grade-6-first-term", "grade-6-second-term",
    "1st-prep-first-term", "1st-prep-second-term",
    "2nd-prep-first-term", "2nd-prep-second-term",
    "3rd-prep-first-term", "3rd-prep-second-term",
    "1st-secondary-first-term", "1st-secondary-second-term",
    "2nd-secondary-first-term", "2nd-secondary-second-term",
    "3rd-secondary-first-term", "3rd-secondary-second-term",
]);
</script>

<template>
    <h1 class="text-center">Please choose a grade</h1>
    <div class="row row-cols-1 row-cols-md-2 g-2" data-cy="grades">
        <div v-for="grade in grades" :key="grade" class="col">
            <RouterLink :to="'/' + grade" class="text-decoration-none btn btn-primary w-100 p-2 py-3 fs-5">
                {{ removeDashes(grade) }}
            </RouterLink>
        </div>
    </div>
    <hr/>
    <section id="contributors" lang="ar" dir="rtl" class="p-4 p-sm-3">
            <h1 class="text-center">منصه بنك الاسئله لمعلمي علوم لغات القليوبيه</h1>
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
                    <NameCard title="توجيه عام للعلوم بالقليوبية" description="الأستاذ محمد السيد مرسي"/>
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