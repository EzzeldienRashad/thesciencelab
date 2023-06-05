import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/LoginView.vue";

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
        }
    ],
});

router.beforeEach(async (to, from) => {
    let member = await fetch("http://localhost//info/functions/login.php", {
        method: "get",
        credentials: "include",
    });
    member = await member.text();
    if (member != "admin" && member != "contributor" && to.name != "login") {
        return {name: "login"};
    } else if ((member == "admin" || member == "contributor") && to.name == "login") {
        return {name: "home"};
    }
});

export default router;
