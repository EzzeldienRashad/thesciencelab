<script setup>
import {ref, onMounted} from "vue";

const nav = ref(null);
const navbarCollapse = ref(null);
const navbarToggler = ref(null);
const navHeight = ref(50);

function hideNav() {
    if (navbarCollapse.value.classList.contains("show")) navbarToggler.value.click()
}

onMounted(() => {
    navHeight.value = nav.value.offsetHeight;
});
</script>

<template>
    <header>
        <nav ref="nav" class="navbar navbar-expand-sm navbar-light bg-primary fixed-top p-0">
            <div class="container-fluid">
                <a href="/" class="navbar-brand p-0">
                    <img src="/favicon.ico" alt="lab" width="50" height="50">
                </a>
                <button ref="navbarToggler" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div ref="navbarCollapse" class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <RouterLink :to="{name :'home'}" class="nav-link fw-bold">Home</RouterLink>
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
        <RouterView />
    </main>
    <footer @click="hideNav" class="text-center text-bg-primary p-2">
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
</style>