<script setup>
import {ref} from "vue";
import {useRouter} from "vue-router";

const router = useRouter();
const passwordField = ref(null);
const error = ref("");

async function login() {
    let member = await fetch("http://localhost/info/functions/login.php", {
        method: "POST",
        //credentials!
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
    } else {
        error.value = "Wrong password!";
    }
}
</script>

<template>
    <form method="post" @submit.prevent="login" action="http://localhost/info/functions/login.php">
        <label class="form-label">
            Password: <input type="password" name="password" ref="passwordField" class="form-control"/>
        </label>
        <br/>
        <span class="text-danger">{{ error }}</span>
        <br/>
        <input type="submit" value="login" class="btn btn-info"/>
    </form>
</template>