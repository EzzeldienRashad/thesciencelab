<script setup>
import {ref} from "vue";

const member = ref("");
const meetings = ref([])
const form = ref(null);
const msg = ref("");
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
        member.value = userInfo[0];
})
loadMeetings();

function createMeeting() {
    fetch("http://127.0.0.1/thesciencelab/info/functions/videoConference.php", {
        method: "post",
        credentials: "include",
        body: new FormData(form.value)
    })
    .then(() => {
        msg.value = ".....";
        setTimeout(() => {msg.value = "Created successfully!"}, 500);
        form.value.reset();
        loadMeetings();
    })
}
function loadMeetings() {
    fetch("http://127.0.0.1/thesciencelab/info/functions/videoConference.php", {
        method: "get",
        credentials: "include"
    })
    .then(res => res.json())
    .then(data => {
        meetings.value = data["results"];
    })
}
function deleteMeeting(id) {
    if (confirm("Are you sure you want to delete this meeting?")) {
        fetch("http://127.0.0.1/thesciencelab/info/functions/videoConference.php?meetingId=" + encodeURIComponent(id), {
            method: "get",
            credentials: "include"
        })
        .then(() => {
            msg.value = ".....";
            setTimeout(() => {msg.value = "Deleted successfully!"}, 500);
            loadMeetings();
        })
    }
}
</script>

<template>
    <section v-if="member == 'admin'" class="p-2 p-md-5">
        <span class="text-info fs-5">{{ msg }}</span>
        <div>
            <form ref="form" action="get" @submit.prevent="createMeeting" class="fs-5 px-lg-5">
                <label>
                    Meeting name: <input type="text" name="meetingName" autocomplete="off" max="39" pattern="[\w \-]+" class="form-control d-inline-block" required/>
                </label>
                <br/>
                <br/>
                <label class="w-100">
                    Lasts for <input type="number" name="duration" size="10"
                        required/> &nbsp; minutes
                </label>
                <br/>
                <br/>
                <input type="submit" class="btn btn-success fs-5" value="Create meeting"/>
            </form>
        </div>
        <br/>
    </section>
    <h1>Running meetings:</h1>
    <div>
        <div v-for="meeting in meetings" :key="meeting['roomName']" class="m-3">
            <a class="p-2 fs-4" :href="meeting['hostRoomUrl'] ? meeting['hostRoomUrl'] : meeting['roomUrl']">{{ meeting["roomName"].slice(1, -6) }}</a>
            <button v-if="member == 'admin'" class="btn btn-close btn-danger" @click="() => deleteMeeting(meeting['meetingId'])"></button>
        </div>
    </div>
</template>