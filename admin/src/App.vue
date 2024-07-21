<script setup>
import {ref, watch} from "vue";
import {useRouter, useRoute, RouterLink} from "vue-router";

const router = useRouter();
const route = useRoute();
const member = ref("");
watch(() => route.path, () => {
    fetch("http://127.0.0.1/thesciencelab/info/functions/login.php", {
            method: "get",
            credentials: "include",
        })
        .then(res => res.text())
        .then(userInfo => {
            try {
                userInfo = JSON.parse(userInfo);
            } catch (e) {
                
            }
            member.value = userInfo[0];
    });
}, {immediate: true})
function logout() {
    fetch("http://127.0.0.1/thesciencelab/info/functions/logout.php", {
        method: "get",
        credentials: "include"
    })
    .then(router.push({name: "login"}));
}
</script>

<template>
<header v-if="useRoute().path != '/login'">
    <nav class="navbar bg-body-secondary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">TheScienceLab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" tabindex="-1">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title fs-4" id="offcanvasNavbarLabel">TheScienceLab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 fs-5">
                        <li class="nav-item">
                            <RouterLink class="nav-link" to="/">Home</RouterLink>
                        </li>
                        <li class="nav-item" v-if="member == 'admin'">
                            <RouterLink to="/uploaders" class="nav-link">uploaders</RouterLink>
                        </li>
                        <li class="nav-item" v-if="member == 'admin'">
                            <RouterLink to="/uploadedQuestions" class="nav-link">uploaded questions</RouterLink>
                        </li>
                        <li class="nav-item">
                            <RouterLink to="/resources" class="nav-link">Download Resources</RouterLink>
                        </li>
                        <li class="nav-item" v-if="member == 'admin'">
                            <RouterLink to="/tests" class="nav-link" data-cy="runningTests">
                                Running tests
                            </RouterLink>
                        </li>
                        <li class="nav-item">
                            <RouterLink to="/videoConference" class="nav-link">
                                Video Conference
                            </RouterLink>
                        </li>
                        <li class="nav-item">
                            <RouterLink to="/uploadWork" class="nav-link">
                                Upload preparation files
                            </RouterLink>
                        </li>
                        <li class="nav-item">
                            <button @click="logout" class="nav-link" to="/logout">Logout</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<main class="p-1 p-sm-2 mt-5">
    <RouterView/>
</main>
<footer v-if="useRoute().path != '/login'" class="p-3 text-center bg-body-secondary">
    All right reserved for The Science Lab 2022 - {{ new Date().getFullYear() }}
</footer>
</template>

<style scoped>
main {
    min-height: 90vh
}
</style>