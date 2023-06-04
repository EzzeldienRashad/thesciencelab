import {createRouter, createWebHistory} from "vue-router";
import HomePage from "@/views/HomePage.vue";
import GradeGames from "@/views/GradeGames.vue";
import GameView from "@/views/GameView.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: HomePage,
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

export default router;