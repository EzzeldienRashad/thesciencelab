<script setup>
import {ref} from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const usernameField = ref(null);
const arabicnameField = ref(null);
const schoolField = ref("");
const administrationField = ref("");
const codeField = ref("");
const nationalIDField = ref("");
const error = ref("");
const done = ref("");

async function addInfo() {
    let resp = await fetch("http://127.0.0.1/info/functions/addInfo.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "username": usernameField.value.value,
            "arabicname": arabicnameField.value.value,
            "school": schoolField.value.value,
            "administration": administrationField.value.value,
            "code": codeField.value.value,
            "nationalID": nationalIDField.value.value,
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
    } else if (resp == "wrong") {
        done.value = "";
        error.value = "....";
        setTimeout(() => {error.value = "*This username doesn't exist!"}, 500);
    } else {        
        done.value = "";
        error.value = "....";
        setTimeout(() => {error.value = "*An error has occurred!"}, 500);
    }
}
</script>

<template>
    <div class="d-flex justify-content-center">
        <form method="post" @submit.prevent="addInfo" action="http://127.0.0.1/info/functions/addInfo.php" class="w-75 py-3">
            <label class="form-label fs-4" for="username">Existing username: </label>
            <input type="text" name="username" id="username" ref="usernameField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="username" required/>
            <hr/>
            <label class="form-label fs-4" for="arabicname">Arabic name: </label>
            <input type="text" name="arabicname" id="arabicname" ref="arabicnameField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="arabicname" required/>
            <br/>
            <label class="form-label fs-4" for="school">School: </label>
            <input type="text" name="school" id="school" ref="schoolField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="school" required/>
            <br/>
            <label class="form-label fs-4" for="administration">Administration: </label>
            <input type="text" name="administration" id="administration" ref="administrationField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="phone" required/>
            <br/>
            <label class="form-label fs-4" for="code">Code: </label>
            <input type="text" name="code" id="code" ref="codeField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="phone" required/>
            <br/>
            <label class="form-label fs-4" for="nationalID">National ID: </label>
            <input type="text" name="nationalID" id="nationalID" ref="nationalIDField" class="form-control p-1 p-sm-3 rounded-4 fs-4 w-100" data-cy="phone" required/>
            <br/>
            <span class="text-danger fs-5">{{ error }}</span>
            <span class="text-success fs-5">{{ done }}</span>
            <br/>
            <input type="submit" value="Add Info" class="btn btn-info fs-3 p-3 rounded-4 fw-bold"/>
        </form>
    </div>
</template>

<style scoped>
input[type="radio"] {
    transform: scale(2);
}
</style>