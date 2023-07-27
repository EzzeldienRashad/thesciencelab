import {createRouter, createWebHistory} from "vue-router";
import {removeDashes} from "@/modules.js";
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
        component: AboutUs,
        meta: {title: "About Us | The Science Lab"}
    },
    {
        path: "/contact",
        name: "contact",
        component: ContactUs,
        meta: {title: "Contact Us | The Science Lab"}
    },
    {
        path: "/:grade",
        name: "grade",
        component: GradeGames,
    },
    {
        path: "/:grade/:game",
        name: "game",
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

router.beforeEach((to) => {
    // close navbar
    if (document.getElementById("navigation") && 
    document.getElementById("navigation").classList.contains("show") && 
    document.getElementById("navbar-toggler")) {
        document.getElementById("navbar-toggler").click();
    }
    document.title = to.meta.title || "The Science Lab"
    if (to.name == "grade") {
        document.title = removeDashes(to.params.grade) + " | The Science Lab";
    }
    if (to.name == "game") {
        document.title = removeDashes(to.params.game) + " game | " + removeDashes(to.params.grade) + " | The Science Lab";
    }
});

export default router;