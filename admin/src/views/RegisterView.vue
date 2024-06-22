<script setup>
import {ref} from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const usernameField = ref(null);
const arabicnameField = ref(null);
const phoneField = ref(null);
const subjectField = ref("");
const error = ref("");
const done = ref("");

async function register() {
    let resp = await fetch("http://127.0.0.1/thesciencelab/info/functions/register.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "username": usernameField.value.value,
            "arabicname": arabicnameField.value.value,
            "phone": phoneField.value.value,
            "subject": subjectField.value
        })
    });
    resp = await resp.text();
    if (resp == "successful") {
        done.value = "....";
        error.value = "";
        setTimeout(() => {done.value = "*Added successfully!"}, 500);
    } else if (resp == "logout") {
        done.value = "";
        error.value = "....";
        setTimeout(() => {error.value = "*You are not logged in!"}, 500);
        setTimeout(() => {router.push({name: "login"})}, 1500);
    } else {        
        done.value = "";
        error.value = "....";
        setTimeout(() => {error.value = "*An error has occurred!"}, 500);
    }
}
</script>

<template>
    <div class="d-flex justify-content-center">
        <form method="post" @submit.prevent="register" action="http://127.0.0.1/thesciencelab/info/functions/register.php" class="w-75 py-3">
            <span class="text-danger fs-5">{{ error }}</span>
            <span class="text-success fs-5">{{ done }}</span>
            <br/>
            <label class="form-label fs-4" for="username">Username: </label>
            <input type="text" name="username" id="username" ref="usernameField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="username" required/>
            <br/>
            <label class="form-label fs-4" for="arabicname">Arabic name: </label>
            <input type="text" name="arabicname" id="arabicname" ref="arabicnameField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="arabicname" required/>
            <br/>
            <label class="form-label fs-4" for="phone">Phone number: </label>
            <input type="text" name="arabicname" id="phone" ref="phoneField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="phone" required/>
            <br/>
            <div class="d-inline-block">
                <input type="radio" name="subject" value="physics" id="physics" v-model="subjectField"/>&nbsp;&nbsp;
                <label class="fs-4" for="physics"> physics</label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="d-inline-block">
                <input type="radio" name="subject" value="chemistry" id="chemistry" v-model="subjectField"/>&nbsp;&nbsp;
                <label class="fs-4" for="chemistry"> chemistry</label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="d-inline-block">
                <input type="radio" name="subject" value="biology" id="biology" v-model="subjectField"/>&nbsp;&nbsp;
                <label class="fs-4" for="biology"> biology</label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="d-inline-block">
                <input type="radio" name="subject" value="none" id="science" v-model="subjectField"/>&nbsp;&nbsp;
                <label class="fs-4" for="science"> science</label>
            </div>
            <br/>
            <br/>
            <input type="submit" value="Register" class="btn btn-info fs-3 p-3 rounded-4 fw-bold"/>
        </form>
    </div>
</template>

<style scoped>
input[type="radio"] {
    transform: scale(2);
}
</style>