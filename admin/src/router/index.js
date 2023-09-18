import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import GamesView from "@/views/GamesView.vue";
import ControlPanel from "@/views/ControlPanel.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
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
            path: "/:grade",
            component: GamesView
        },
        {
            path: "/:grade/:game",
            component: ControlPanel
        },
    ],
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
    member = await member.text();
    if (member != "admin" && to.name != "login") {
        return {name: "login"};
    } else if ((member == "admin") && to.name == "login") {
        return {name: "home"};
    }
});

export default router;
