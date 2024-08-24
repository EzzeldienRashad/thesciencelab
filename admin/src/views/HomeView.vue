<script setup>
import {ref, inject} from "vue";
import NameCard from "@/components/NameCard.vue";
import { RouterLink } from "vue-router";
import UploadedQuestions from "../components/UploadedQuestions.vue";
import Uploaders from "../components/Uploaders.vue";

const member = inject("member");
const documentWidth = inject("documentWidth");
</script>

<template>
<section>
    <div id="bg-image" class="p-3 py-5 text-light">
        <h1 class="text-center">منصه بنك الأسئله لمعلمي علوم لغات القليوبيه</h1>
        <div class="pt-5" :class="{'w-50': documentWidth > 1000}">
            <span class="display-2">We make Science fun, interactive, and easy to learn!</span>
            <br/>
            <RouterLink to="/selectGrade" class="btn border border-5 rounded-4 border-info mt-5 ms-5 p-3 fs-3 text-light fw-bold">
                Start uploading Questions &nbsp;
                <font-awesome-icon class="next-arrow" icon="fa-solid fa-right-long"/>
            </RouterLink>
        </div>
        <br/>
        <br/>
    </div>
</section>
<section class="text-center pt-2">
    <h2 class="display-2 d-inline-block border-bottom border-3 border-dark-subtle">Our features</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 p-5 g-5 justify-content-around">
        <div>
            <RouterLink to="/tests"  class="card m-xl-5 text-decoration-none">
                <img src="@/assets/images/test.webp" class="card-img-top" alt="online test">
                <div class="card-body">
                    <h5 class="card-title">Online Tests</h5>
                    <p class="card-text">We offer online tests to evaluate students' levels and help them prepare for exams</p>
                </div>
                <ul class="list-group list-group-flush fst-italic text-start">
                    <li class="list-group-item">1- Create a test</li>
                    <li class="list-group-item">2- Give students the test code</li>
                    <li class="list-group-item">3- See the students' scores in the dashboard</li>
                </ul>
            </RouterLink>
        </div>
        <div>
            <RouterLink to="/videoConference"  class="card m-xl-5 text-decoration-none">
                <img src="@/assets/images/videoConference.webp" class="card-img-top" alt="video conference">
                <div class="card-body">
                    <h5 class="card-title">Video Conference</h5>
                    <p class="card-text">Meetings are essential for good communication between teachers and arrangement of future plans</p>
                </div>
                <ul class="list-group list-group-flush fst-italic text-start">
                    <li class="list-group-item">1- The admin will create a meeting</li>
                    <li class="list-group-item">2- Teachers enter using the meeting code</li>
                    <li class="list-group-item">3- Important plans and future events can be easily discussed</li>
                </ul>
            </RouterLink>
        </div>
        <div>
            <RouterLink to="/uploadPreparationFiles"  class="card m-xl-5 text-decoration-none">
                <img src="@/assets/images/preparationFiles.webp" class="card-img-top" alt="uploading preparation files">
                <div class="card-body">
                    <h5 class="card-title">Uploading Preparation Files</h5>
                    <p class="card-text">A new method for showing teachers' work and preparation for the next sessions</p>
                </div>
                <ul class="list-group list-group-flush fst-italic text-start">
                    <li class="list-group-item">1- Write your preparation files</li>
                    <li class="list-group-item">2- Take a clear photo of the files</li>
                    <li class="list-group-item">3- Upload the photos for review</li>
                </ul>
            </RouterLink>
        </div>
    </div>
</section>
<section class="px-2 px-md-5 row">
    <div class="border-end border-3 border-dark-subtle col-12 col-sm-6">
        <h2 class="display-3 d-inline-block pb-md-5">Download Resources</h2>
        <p class="fs-3 w-75">
            A collection of helpful books to help teachers know the curriculum better and get new question ideas
        </p>
        <RouterLink to="/resources" class="btn rounded-4 btn-info mt-md-5 ms-5 mb-3 mb-md-1 p-3 fs-3 fw-bold"> 
            Get Now! &nbsp;
            <font-awesome-icon class="next-arrow" icon="fa-solid fa-right-long"/>
        </RouterLink>
        <br/>
        <a href="https://www.facebook.com/share/p/5shkj7auaQE5z1Dy/?mibextid=A7sQZp" class="fs-4">Click here to download more resources!</a>
        <br/>
        <br/>
    </div>
    <div class="text-center col-12 col-sm-6">
        <RouterLink to="/resources">
            <img src="@/assets/images/books/physics-for-neet.png" class="resources-img" alt="resources"/>
        </RouterLink>
    </div>
</section>
<section v-if="member == 'admin'" class="mt-5 px-3">
    <div class="text-center">
        <h2 class="display-2 d-inline-block border-bottom border-3 border-dark-subtle">Teachers' Statistics</h2>
    </div>
    <div>
        <Uploaders id="uploaders" class="p-2 mb-3 bg-light-subtle"/>
        <UploadedQuestions id="uploadedQuestions" class="p-2 mt-3 bg-light-subtle"/>
    </div>
</section>
<section id="contributors" lang="ar" dir="rtl" class="p-4 p-sm-3">
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
section #bg-image {
    background-image: url("@/assets/images/mainBackground.webp");
    background-repeat: no-repeat;
    background-size: cover;
}
h1 {
    font-family: "Reem Kufi Fun", sans-serif;
    font-size: 50px;
}
#contributors h2 {
    font-family: "Amiri", serif;
    font-weight: 400;
    font-style: normal;
}
.resources-img {
    max-width: 100%;
}
</style>