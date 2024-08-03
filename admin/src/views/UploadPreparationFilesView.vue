<script setup>
import {ref, inject, watch} from "vue";

const memberName = ref("");
const member = inject("member");
const msg = ref("");
const msgColor = ref("");
const form = ref(null);
const files = ref([])
const members = ref([])

watch(memberName, loadFiles);
if (member.value != "admin") {
    memberName.value = userInfo[1];
    loadFiles()
} else {
    fetch("http://127.0.0.1/thesciencelab/info/functions/printUploadedPreparationFiles.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.json())
    .then(res => members.value = res)
}

function addFile() {
    msgColor.value = "primary";
    msg.value = ".....uploading";
    fetch("http://127.0.0.1/thesciencelab/info/functions/uploadPreparationFiles.php", {
        method: "post",
        credentials: "include",
        body: new FormData(form.value)
    })
    .then(res => res.text())
    .then(res => {
        switch (res) {
            case "successful":
                msgColor.value = "success"
                setTimeout(() => msg.value = "*added successfully!", 500);
                msg.value = "added successfully!";
                loadFiles();
                break;
            case "big":
                msgColor.value = "danger"
                setTimeout(() => msg.value = "*The image uploaded is too big!", 500);
                break;
            case "typeerr":
                msgColor.value = "danger"
                setTimeout(() => msg.value = "*Only images are allowed!", 500);
                break;
            default:
                msgColor.value = "danger"
                setTimeout(() => msg.value = "*An error has occurred!", 500);
                break;
        }
    })
}
function loadFiles() {
    fetch("http://127.0.0.1/thesciencelab/info/functions/printUploadedPreparationFiles.php?username=" + memberName.value, {
        method: "get",
        credentials: "include"
    })
    .then(res => res.json())
    .then(res => files.value = res);
}
function deleteFile(file) {
    if (confirm("Are you sure you want to delete this file")) fetch("http://127.0.0.1/thesciencelab/info/functions/deleteFile.php?username=" + memberName.value + "&uploader=" + memberName.value + "&file=" + file)
    .then(loadFiles);
}
</script>

<template>
    <section v-if="memberName" class="p-2">
        <form v-if="member != 'admin'" ref="form" method="post" type="multipart/form-data">
            <br/>
            Upload a file: <input id="fileUpload" type="file" name="preparationFile" @change="addFile()"/>
            <br/>
            <span :class="'text-' + msgColor">{{ msg }}</span>
        </form>
        <br/>
        <div class="d-flex flex-column-reverse">
            <div class="w-100" v-for="file in files" :key="file">
                <img class="p-2" :src="'http://127.0.0.1/thesciencelab/info/preparationFiles/' + memberName + '/' + file"/>
                <button @click="deleteFile(file)" class="bg-light border-3 border-danger rounded-2 p-2 text-danger fw-bold">delete</button>
            </div>
        </div>
    </section>
    <section v-else class="p-2 row">
        <button v-for="memberInfo in members" :key="memberInfo" @click="memberName = memberInfo[0]" class="bg-light p-4 rounded-3 col-6 col-lg-4 col-xl-3">{{ memberInfo[1] }}</button>
    </section>
</template>

<style scoped>
input[type="file"] {
    width:100vw;
    max-width:100%;  
    white-space: normal;
    word-wrap: break-word; 
} 
img {
    max-width: min(80%, 1000px);
}
</style>