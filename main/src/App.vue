<script setup>
import {ref, onBeforeUnmount, provide} from "vue";
import logo from "@/assets/icons/logo.webp";

const nav = ref(null);
const navbarCollapse = ref(null);
const navbarToggler = ref(null);
const navHeight = ref(50);
const documentWidth = ref(document.body.clientWidth);
const theme = ref("primary")

provide("documentWidth", documentWidth);
provide("theme", theme);

function hideNav() {
    if (navbarCollapse.value.classList.contains("show")) navbarToggler.value.click()
}
function assignDocumentWidth() {
    documentWidth.value = document.body.clientWidth;
}
function changeTheme(newTheme) {
    theme.value = newTheme;
}

addEventListener("load", () => {
    navHeight.value = nav.value.offsetHeight;
    addEventListener("resize", assignDocumentWidth);
});
onBeforeUnmount(() => {
    removeEventListener("resize", assignDocumentWidth);
});
</script>

<template>
    <header>
        <nav ref="nav" class="navbar navbar-expand-sm navbar-light fixed-top p-1 fs-5" :class="'bg-' + theme">
            <div class="container-fluid">
                <a href="/" class="navbar-brand p-0">
                    <img :src="logo" alt="Main Page" width="50" height="50">
                </a>
                <button ref="navbarToggler" class="navbar-toggler" id="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div ref="navbarCollapse" class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <RouterLink :to="{name :'home'}" class="nav-link fw-bold">Home</RouterLink>
                        </li>
                        <li class="nav-item">
                            <RouterLink :to="{name :'about'}" class="nav-link fw-bold">About us</RouterLink>
                        </li>
                        <li class="nav-item">
                            <RouterLink :to="{name :'contact'}" class="nav-link fw-bold">Contact us</RouterLink>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.youtube.com/@TheScienceLabGroup" class="nav-link fw-bold">Youtube channel</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main @click="hideNav" class="min-vh-100" :style="{marginTop: navHeight + 'px'}">
        <RouterView @changeTheme="newTheme => changeTheme(newTheme)" />
    </main>
    <footer @click="hideNav" class="text-center p-2" :class="'text-bg-' + theme">
        All right reserved for The Science Lab 2022 - {{ new Date().getFullYear() }}
    </footer>
</template>

<style scoped>
.active, .nav-link:hover {
    background-color: ghostwhite;
}
.nav-link {
    padding: 10px;
}
.navbar {
    transition-timing-function: ease;
    transition-duration: 0.5s;
}
</style>