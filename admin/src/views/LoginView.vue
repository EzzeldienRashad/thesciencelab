<script setup>
import {ref} from "vue";
import {useRouter} from "vue-router";

const router = useRouter();
const passwordField = ref(null);
const error = ref("");
const showPassword = ref(false);

async function login() {
    let member = await fetch("http://127.0.0.1/info/functions/login.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "password": passwordField.value.value,
        })
    });
    member = await member.text();
    if (member == "admin") {
        router.push({name: "home"});
    } else if (member == "lockout") {
        error.value = "....";
        setTimeout(() => {error.value = "You tried too many passwords in a short time, try again after 5 minutes."}, 500);
    } else {
        error.value = "....";
        setTimeout(() => {error.value = "Wrong password!"}, 500);
    }
}
</script>

<template>
    <form method="post" @submit.prevent="login" action="http://127.0.0.1/info/functions/login.php">
        <label class="form-label" for="password">Password: </label>
        <div class="row">
            <div class="col-4">
                <div class="input-group">
                    <input :type="showPassword ? 'text' : 'password'" name="password" ref="passwordField" size="50" class="form-control"/>
                    <span class="input-group-text" @click="showPassword = !showPassword">
                        <font-awesome-icon icon="fa-solid fa-eye" v-if="!showPassword" />
                        <font-awesome-icon icon="fa-solid fa-eye-slash" v-if="showPassword" />
                    </span>
                </div>
            </div>
        </div>
        <br/>
        <span class="text-danger">{{ error }}</span>
        <br/>
        <input type="submit" value="login" class="btn btn-info"/>
    </form>
</template>

<style scoped>
.input-group-text {
    cursor: pointer;
}
</style>