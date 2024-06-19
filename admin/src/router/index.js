import {createRouter, createWebHistory, createWebHashHistory} from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import addInfoView from "@/views/AddInfoView.vue";
import UploadersView from "@/views/UploadersView.vue";
import UploadedQuestionsView from "@/views/UploadedQuestionsView.vue";
import ResourcesView from "@/views/ResourcesView.vue";
import TestsView from "@/views/TestsView.vue";
import GamesView from "@/views/GamesView.vue";
import ControlPanel from "@/views/ControlPanel.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: HomeView,
    },
    {
        path: "/login",
        name: "login",
        component: LoginView
    },
    {
        path: "/register",
        name: "register",
        component: RegisterView
    },
    {
        path: "/addInfo",
        name: "addInfo",
        component: addInfoView
    },
    {
        path: "/uploaders",
        name: "uploaders",
        component: UploadersView
    },
    {
        path: "/uploadedQuestions",
        name: "uploadedQuestions",
        component: UploadedQuestionsView
    },
    {
        path: "/resources",
        name: "resources",
        component: ResourcesView
    },
    {
        path: "/tests",
        name: "tests",
        component: TestsView
    },
    {
        path: "/:grade",
        component: GamesView
    },
    {
        path: "/:grade/:game",
        component: ControlPanel
    },
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});
const hashRouter = createRouter({
    history: createWebHashHistory(),
    routes
});

router.beforeEach(async to => {
    if (document.querySelector(".modal-backdrop")) {
        document.querySelector(".modal-backdrop").remove();
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";
        document.body.classList.remove("modal-open");
    }
    let member = await fetch("http://127.0.0.1/info/functions/login.php", {
        method: "get",
        credentials: "include",
    });
    let memberArr = await member.text();
    try {        
        member = JSON.parse(memberArr)[0];
    } catch (e) {
        member = memberArr;
    }
    if (!["biology", "physics", "chemistry", "admin", "none"].includes(member) && to.name != "login") {
        return {name: "login"};
    } else if (["biology", "physics", "chemistry", "admin", "none"].includes(member) && to.name == "login") {
        return {name: "home"};
    }
});

export default router;
export {hashRouter}
