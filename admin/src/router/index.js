import {createRouter, createWebHistory, createWebHashHistory} from "vue-router";
import HomeView from "@/views/HomeView.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: HomeView,
    },
    {
        path: "/login",
        name: "login",
        component: () => import("@/views/LoginView.vue")
    },
    {
        path: "/register",
        name: "register",
        component: () => import("@/views/RegisterView.vue")
    },
    {
        path: "/addInfo",
        name: "addInfo",
        component: () => import("@/views/AddInfoView.vue")
    },
    {
        path: "/uploaders",
        name: "uploaders",
        component: () => import("@/views/UploadersView.vue")
    },
    {
        path: "/uploadedQuestions",
        name: "uploadedQuestions",
        component: () => import("@/views/UploadedQuestionsView.vue")
    },
    {
        path: "/resources",
        name: "resources",
        component: () => import("@/views/ResourcesView.vue")
    },
    {
        path: "/tests",
        name: "tests",
        component: () => import("@/views/TestsView.vue")
    },
    {
        path: "/videoConference",
        name: "videoConference",
        component: () => import("@/views/VideoConferenceView.vue")
    },
    {
        path: "/uploadWork",
        name: "uploadWork",
        component: () => import("@/views/UploadWorkView.vue")
    },
    {
        path: "/:grade",
        component: () => import("@/views/ShowGamesView.vue")
    },
    {
        path: "/:grade/:game",
        component: () => import("@/views/ControlPanel.vue")
    },
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior() {
        return {top: 1};
    }
});
const hashRouter = createRouter({
    history: createWebHashHistory(),
    routes
});

router.beforeEach(async to => {
    // close navbar
    if (document.getElementById("offcanvasNavbar") && 
    document.getElementById("offcanvasNavbar").classList.contains("show") && 
    document.querySelector("#offcanvasNavbar .btn-close")) {
        document.querySelector("#offcanvasNavbar .btn-close").click();
    }    
    if (document.querySelector(".modal-backdrop")) {
        document.querySelector(".modal-backdrop").remove();
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";
        document.body.classList.remove("modal-open");
    }
    let member = await fetch("http://127.0.0.1/thesciencelab/info/functions/login.php", {
        method: "get",
        credentials: "include",
    });
    let memberArr = await member.text();
    try {        
        member = JSON.parse(memberArr)[0];
    } catch (e) {
        member = memberArr;
    }
    if (!["biology", "physics", "chemistry", "admin", "science"].includes(member) && to.name != "login") {
        return {name: "login"};
    } else if (["biology", "physics", "chemistry", "admin", "science"].includes(member) && to.name == "login") {
        return {name: "home"};
    }
});

export default router;
export {hashRouter}
