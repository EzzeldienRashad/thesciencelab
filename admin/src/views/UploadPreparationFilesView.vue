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
            memberName.value = userInfo[1];
    });
} else {
    fetch("http://127.0.0.1/thesciencelab/info/functions/printUploadedPreparationFiles.php", {
        method: "get",
        credentials: "include",
    })
    .then(res => res.json())
    .then(res => members.value = res)
}

function addFile() {
    msgColor.value = "info";
    msg.value = "uploading";
    document.getElementsByClassName("spinner")[0].classList.remove("visually-hidden")
    document.getElementsByClassName("tick")[0].classList.add("visually-hidden")
    fetch("http://127.0.0.1/thesciencelab/info/functions/uploadPreparationFiles.php?memberName=" + memberName.value, {
        method: "post",
        credentials: "include",
        body: new FormData(form.value)
    })
    .then(res => res.text())
    .then(res => {
        setTimeout(() => {
            document.getElementsByClassName("spinner")[0].classList.add("visually-hidden")
            switch (res) {
                case "successful":
                    msgColor.value = "success"
                    msg.value = "added successfully!";
                    document.getElementsByClassName("tick")[0].classList.remove("visually-hidden")
                    msg.value = "added successfully!";
                    loadFiles();
                    break;
                case "big":
                    msgColor.value = "danger"
                    msg.value = "The file uploaded is too big!";
                    break;
                case "typeerr":
                    msgColor.value = "danger"
                    msg.value = "Only images and word files are allowed!";
                    break;
                default:
                    msgColor.value = "danger"
                    msg.value = "An error has occurred!";
                    break;
            }
        }, 500);
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
    <div class="row">
        <div class="col-12 col-lg-6">
            <form ref="form" method="post" type="multipart/form-data" class="bg-white border border-2 text-center text-primary fw-bold fs-4 p-3 rounded-3 shadow">
                <label for="fileUpload" role="button" class="border border-2 w-100 py-5 mb-2" style="border-style: dashed !important;">
                    <font-awesome-icon icon="fa-solid fa-cloud-arrow-up" size="3x"/>
                    <br/>
                    <br/>
                    Upload a file
                </label>
                <span :class="'text-' + msgColor">
                    <div class="spinner-border visually-hidden spinner" role="status">
                        <span class="visually-hidden">Uploading...</span>
                    </div>
                    <font-awesome-icon icon="fa-solid fa-check" class="tick visually-hidden"/>
                    &nbsp;&nbsp;&nbsp;
                    <span>{{ msg }}</span>
                </span>
                <input id="fileUpload" type="file" name="preparationFile" class="visually-hidden" @change="addFile()"/>
                <br/>
            </form>
        </div>
        <div class="col-12 col-lg-6 my-2">
            <div class="row">
                <a href="http://127.0.0.1/thesciencelab/info/preparationFiles/g4plan.pdf" download class="btn pb-3 fs-5 col-12 col-sm-6 rounded-0 border border-2 border-danger" style="background-color: #ffddd0">
                    <font-awesome-icon icon="fa-solid fa-file-pdf" size="2x" class="text-danger" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                    خرائط الصف الرابع&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-danger-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                </a>
                <a href="http://127.0.0.1/thesciencelab/info/preparationFiles/g4notebook.pdf" download class="btn pb-3 fs-5 col-12 col-sm-6 rounded-0 border border-2 border-danger" style="background-color: #ffddd0">
                    <font-awesome-icon icon="fa-solid fa-file-pdf" size="2x" class="text-danger" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                    دفتر الصف الرابع&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-danger-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                </a>
                <a href="http://127.0.0.1/thesciencelab/info/preparationFiles/g5plan.pdf" download class="btn pb-3 fs-5 col-12 col-sm-6 rounded-0 border border-2 border-danger" style="background-color: #ffddd0">
                    <font-awesome-icon icon="fa-solid fa-file-pdf" size="2x" class="text-danger" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                    خرائط الصف الخامس&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-danger-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                </a>
                <a href="http://127.0.0.1/thesciencelab/info/preparationFiles/g5notebook.pdf" download class="btn pb-3 fs-5 col-12 col-sm-6 rounded-0 border border-2 border-danger" style="background-color: #ffddd0">
                    <font-awesome-icon icon="fa-solid fa-file-pdf" size="2x" class="text-danger" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                    دفتر الصف الخامس&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-danger-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                </a>
                <a href="http://127.0.0.1/thesciencelab/info/preparationFiles/g6plan.pdf" download class="btn pb-3 fs-5 col-12 col-sm-6 rounded-0 border border-2 border-danger" style="background-color: #ffddd0">
                    <font-awesome-icon icon="fa-solid fa-file-pdf" size="2x" class="text-danger" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                    خرائظ الصف السادس&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-danger-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                </a>
                <a href="http://127.0.0.1/thesciencelab/info/preparationFiles/g6notebook.pdf" download class="btn pb-3 fs-5 col-12 col-sm-6 rounded-0 border border-2 border-danger" style="background-color: #ffddd0">
                    <font-awesome-icon icon="fa-solid fa-file-pdf" size="2x" class="text-danger" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                    دفتر الصف السادس&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-danger-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                </a>
            </div>
            <ul class="nav nav-tabs mt-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#images-tab-pane" type="button" role="tab" aria-controls="images-tab-pane" aria-selected="true">Images</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#word-documents-tab-pane" type="button" role="tab" aria-controls="word-documents-tab-pane" aria-selected="false">Word Documents</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="images-tab-pane" role="tabpanel" aria-labelledby="images-tab" tabindex="0">
                    <div class="d-flex flex-column-reverse">
                        <div class="w-100" v-for="file in files.filter(file => !['doc', 'docx'].includes(file.split('.').pop()))" :key="file">
                            <img class="p-2" :src="'http://127.0.0.1/thesciencelab/info/preparationFiles/' + memberName + '/' + file"/>
                            <button @click="deleteFile(file)" class="text-danger rounded-2 p-2 text-danger fw-bold trash">
                                <font-awesome-icon icon="fa-solid fa-trash-can" size="2x"/>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="word-documents-tab-pane" role="tabpanel" aria-labelledby="word-documents-tab" tabindex="0">
                    <div class="d-flex flex-column-reverse">
                        <div class="w-100" v-for="file in files.filter(file => ['doc', 'docx'].includes(file.split('.').pop()))" :key="file">
                            <a :href="'http://127.0.0.1/thesciencelab/info/preparationFiles/' + memberName + '/' + file" download class="btn pb-3 m-2 fs-5" style="background-color: #d0f0ff">
                                <font-awesome-icon icon="fa-solid fa-file-word" size="2x" class="text-primary" style="transform: translateY(20%);"/>&nbsp;&nbsp;
                                {{ file }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <font-awesome-icon icon="fa-solid fa-download" size="2x" class="text-primary-emphasis" style="transform: translateY(20%) scale(0.8, 0.8);"/>
                            </a>
                            <button @click="deleteFile(file)" class="text-danger rounded-2 p-2 text-danger fw-bold trash">
                                <font-awesome-icon icon="fa-solid fa-trash-can" size="2x"/>
                            </button>
                        </div>
                    </div>
                </div>
            </div>        
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
button.trash {
	background: none;
	color: inherit;
	border: none;
	padding: 0;
	font: inherit;
	outline: inherit;
}
</style>