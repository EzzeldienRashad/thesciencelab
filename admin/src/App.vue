<script setup>
import {ref, watch, provide, onBeforeUnmount} from "vue";
import {useRouter, useRoute, RouterLink} from "vue-router";

const router = useRouter();
const route = useRoute();
const member = ref("");
const username = ref("");
const documentWidth = ref(document.body.clientWidth);
const navHeight = ref(50);
const nav = ref(null);
const navbarCollapse = ref(null);
const navbarToggler = ref(null);

provide("documentWidth", documentWidth);
provide("member", member)
provide("username", username)

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
            username.value = userInfo[1];
    });
}, {immediate: true})
function logout() {
    fetch("http://127.0.0.1/thesciencelab/info/functions/logout.php", {
        method: "get",
        credentials: "include"
    })
    .then(() => {
        document.cookie = "tokenKey=; expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;domain=thesciencelab.infinityfreeapp.com"; 
        document.cookie = "tokenValue=; expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;domain=thesciencelab.infinityfreeapp.com";
        router.push({name: "login"}); 
    });
}
function assignDocumentWidth() {
    documentWidth.value = document.body.clientWidth;
    navHeight.value = nav.value.offsetHeight
}

addEventListener("load", () => {
    navHeight.value = nav.value.offsetHeight;
    addEventListener("resize", assignDocumentWidth);
});
onBeforeUnmount(() => {
    removeEventListener("resize", assignDocumentWidth);
});
function hideNav() {
    if (navbarCollapse.value) {
        if (navbarCollapse.value.classList.contains("show")) navbarToggler.value.click()
    }
}
</script>

<template>
<header v-if="useRoute().path != '/login'">
    <nav ref="nav" class="navbar navbar-expand-lg fixed-top bg-primary bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/favicon.ico" alt="the science lab logo" height="40"/>&nbsp;&nbsp;&nbsp;
                TheScienceLab
            </a>
            <button ref="navbarToggler" class="navbar-toggler" id="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div ref="navbarCollapse" class="collapse navbar-collapse justify-content-end" id="navigation">
                <ul class="navbar-nav fs-5">
                    <li class="nav-item px-1">
                        <RouterLink class="nav-link" to="/">Home</RouterLink>
                    </li>
                    <li class="nav-item px-1">
                        <RouterLink to="/tests" class="nav-link" data-cy="runningTests">
                            Running tests
                        </RouterLink>
                    </li>
                    <li class="nav-item px-1">
                        <RouterLink to="/videoConference" class="nav-link">
                            Video Conference
                        </RouterLink>
                    </li>
                    <li class="nav-item px-1">
                        <RouterLink to="/uploadPreparationFiles" class="nav-link">
                            Upload preparation files
                        </RouterLink>
                    </li>
                    <li class="nav-item px-1">
                        <button @click="logout" class="nav-link" to="/logout">Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main @click="hideNav" :style="{marginTop: navHeight + 'px'}">
    <RouterView/>
</main>
<footer @click="hideNav" v-if="useRoute().path != '/login'" class="p-3 text-center bg-body-secondary">
    All right reserved for The Science Lab 2022 - {{ new Date().getFullYear() }}
</footer>
</template>

<style scoped>
main {
    min-height: 90vh;
}
</style>