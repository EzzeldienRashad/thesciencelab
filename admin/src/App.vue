<script setup>
import {useRouter, useRoute} from "vue-router";

const router = useRouter();

function logout() {
    fetch("http://127.0.0.1/info/functions/logout.php", {
        method: "get",
        credentials: "include"
    })
    .then(router.push({name: "login"}));
}
</script>

<template>
    <main class="p-2 p-sm-3">
        <RouterView/>
    </main>
    <footer v-if="useRoute().path != '/login'" class="p-3">
        <ul class="pagination">
            <li class="page-item">
                <RouterLink to="/" class="page-link">main page</RouterLink>
            </li>
            <li class="page-item">
                <button @click="logout" class="page-link">logout</button>
            </li>
        </ul>
    </footer>
</template>