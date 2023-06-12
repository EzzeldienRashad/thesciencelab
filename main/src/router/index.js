import {createRouter, createWebHistory} from "vue-router";
import HomePage from "@/views/HomePage.vue";
import AboutUs from "@/views/AboutUs.vue";
import ContactUs from "@/views/ContactUs.vue";
import GradeGames from "@/views/GradeGames.vue";
import GameView from "@/views/GameView.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: HomePage,
    },
    {
        path: "/about",
        name: "about",
        component: AboutUs
    },
    {
        path: "/contact",
        name: "contact",
        component: ContactUs
    },
    {
        path: "/:grade",
        component: GradeGames
    },
    {
        path: "/:grade/:game",
        component: GameView
    }
];
const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "active",
    scrollBehavior() {
        return {top: 1};
    }
});

router.beforeEach(() => {
    if (document.getElementById("navigation") && 
    document.getElementById("navigation").classList.contains("show") && 
    document.getElementById("navbar-toggler")) {
        document.getElementById("navbar-toggler").click();
    }
});

export default router;