<script setup>
import {ref} from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const password = ref("");
const username = ref("");
const rememberme = ref(false);
const error = ref("");
const showPassword = ref(false);

async function login() {
    let member = await fetch("http://127.0.0.1/thesciencelab/info/functions/login.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "username": username.value,
            "password": password.value,
            "rememberme": rememberme.value
        })
    });
    let memberArr = await member.text();
    try {
        member = JSON.parse(memberArr)[0];
    } catch (e) {
        member = memberArr;
    }
    if (["biology", "physics", "chemistry", "admin", "science"].includes(member)) {
        router.push({name: "home"});
    } else if (member == "blocked") {
        error.value = "....";
        setTimeout(() => {error.value = "*You tried too many passwords in a short time, try again after 5 minutes."}, 500);
    } else if (member == "not allowed") {
        error.value = "....";
        setTimeout(() => {error.value = "*Wrong username or password!"}, 500);
    } else {
        error.value = "....";
        setTimeout(() => {error.value = "*An error has occurred! Please refresh the page."}, 500);
    }
}
</script>

<template>
    <div class="d-flex justify-content-center">
        <form method="post" @submit.prevent="login" action="http://127.0.0.1/thesciencelab/info/functions/login.php" class="w-75 py-3">
            <span class="text-danger fs-5">{{ error }}</span>
            <br/>
            <label class="form-label fs-4" for="username">Username: </label>
            <input type="text" name="username" id="username" v-model="username" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="username" required/>
            <br/>
            <label class="form-label fs-4"  for="password">Password: </label>
            <div class="w-100">
                <div class="input-group">
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password" v-model="password" class="form-control p-1 p-sm-3 rounded-start-4 fs-4" data-cy="password" required/>
                    <span class="input-group-text rounded-end-4" @click="showPassword = !showPassword">
                        <font-awesome-icon icon="fa-solid fa-eye" v-if="!showPassword" />
                        <font-awesome-icon icon="fa-solid fa-eye-slash" v-if="showPassword" />
                    </span>
                </div>
            </div>
            <br/>
            <input type="checkbox" name="rememberme" id="rememberme" v-model="rememberme" data-cy="rememberme"/>&nbsp;
            <label class="form-label fs-4" for="rememberme"> remember me</label>
            <br/>
            <input type="submit" value="login" class="btn btn-info fs-3 p-3 rounded-4 fw-bold"/>
        </form>
    </div>
</template>

<style scoped>
.input-group-text {
    cursor: pointer;
}
</style>