import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";
import GradeGames from "@/views/GradeGames.vue";
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
            component: GradeGames
        },
        {
            path: "/:grade/:game",
            component: ControlPanel
        },
    ],
});

router.beforeEach(async to => {
    let member = await fetch("http://localhost/info/functions/login.php", {
        method: "get",
        //credentials!
    });
    member = await member.text();
    if (member != "admin" && to.name != "login") {
        return {name: "login"};
    } else if ((member == "admin") && to.name == "login") {
        return {name: "home"};
    }
});

export default router;
