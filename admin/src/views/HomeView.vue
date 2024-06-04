<script setup>
import {ref} from "vue";
import removeDashes from "@/modules/removeDashes.js"

const grades = ref([
    "grade-4-first-term", "grade-4-second-term",
    "grade-5-first-term", "grade-5-second-term",
    "grade-6-first-term", "grade-6-second-term",
    "1st-prep-first-term", "1st-prep-second-term",
    "2nd-prep-first-term", "2nd-prep-second-term",
    "3rd-prep-first-term", "3rd-prep-second-term",
]);
</script>

<template>
    <h1 class="text-center">Please choose a grade</h1>
    <div class="row row-cols-1 row-cols-md-2 g-2" data-cy="grades">
        <div v-for="grade in grades" :key="grade" class="col">
            <RouterLink :to="'/' + grade" class="text-decoration-none btn btn-primary w-100 p-2 py-3">
                {{ removeDashes(grade) }}
            </RouterLink>
        </div>
    </div>
    <hr/>
    <section lang="ar" dir="rtl" class="p-4 p-sm-3">
            <div class="row">
                <div class="col-6">
                    <h2>تحت رعاية:</h2>
                    <ul class="fs-4 bullet-arrow">
                        <li>مديرية التربية والتعليم بالقليوبية</li>
                        <li>التوجيه العام لمادة العلوم</li>
                        <li>ادارة العبور التعليمية</li>
                        <li>مدرسة على بن أبى طالب الرسمية لغات</li>
                    </ul>
                </div>
                <div class="col-6 pe-2 border-end">
                    <h2>تحت إشراف ورعاية:</h2>
                    <ul class="fs-4 ps-0">
                        <li><b>دكتورة</b>/ سماح ابراهيم، مدير المديرية</li>
                        <li><b>أ</b>/ سعيد ندا، وكيل المديرية</li>
                        <li><b>دكتورة</b>/ فوزية عبد الفتاح، مديرة الشئون التربوية</li>
                        <li><b>مستر</b>/ أيمن فاضل، التوجيه العام لمادة Science</li>
                        <li><b>الدكتور</b>/ سعد عسل، مدير الإدارة</li>
                        <li><b>أ</b>/ حسام زهدى، وكيل الادارة</li>
                        <li><b>أ</b>/ محمد عبد الحليم السعدنى، مدير مدرسة على بن أبى طالب الرسمية للغات</li>
                        <li>أسرة مادة Science بالمدرسة</li>
                        <li><b>مصمم الموقع</b>/ الطالب عزالدين رشاد</li>
                        <li>مدرسة ستيم العبور</li>
                        <li><b>أ</b>/ طارق على سليمان، مشرف المادة بالمدرسة</li>
                    </ul>
                </div>
            </div>
        </section>
</template>