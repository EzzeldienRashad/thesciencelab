import {createRouter, createWebHistory} from "vue-router";
import HomePage from "@/views/HomePage.vue"
import GradeGames from "@/views/GradeGames.vue"

const routes = [
    {
        path: "/",
        name: "home",
        component: HomePage,
    },
    {
        path: "/:grade",
        component: GradeGames
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

export default router;