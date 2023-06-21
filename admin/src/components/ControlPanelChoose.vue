<script setup>
const props = defineProps(["questions", "msg", "msgColor", "deleteQuestion", "addQuestion"]);
const {questions, msg, msgColor, deleteQuestion, addQuestion} = props;
</script>

<template>
    <div v-for="(question, index) in questions" :key="question[0]" class="card mb-2 border-dark">
        <div class="card-header p-2 fw-bold">
            {{ question[0] }}
            <button class='btn btn-danger btn-close float-end' @click="deleteQuestion(index)"></button>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div v-for="answer in question[1]" class="col-12 col-sm-6 col-lg-3 border px-1" :class="{'text-bg-success': answer == question[1][question[2] - 1]}">
                    <div class="h-100">{{ answer }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="overlay">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content bg-light">
                <div class="modal-body">
                    <div v-if="msg" class='alert text-center h3 p-2 d-flex align-items-center' :class="'alert-' + msgColor">{{ msg }}</div>
                    <button class="btn btn-danger btn-close float-end" data-bs-dismiss="modal" aria-label="close"></button>
                    <form id="form" method="post" @submit.prevent="addQuestion" class="mt-2">
                        <div class="row g-0">
                            <label class="form-label">
                                Question: <input type="text" name="question" class="form-control" autocomplete="off" required/>
                            </label>
                        </div>
                        <fieldset class="row">
                            <legend>Answers:</legend>
                            <label class="form-label col-md-6">
                                First answer: <input type="text" name="first" class="form-control" autocomplete="off" required/>
                            </label>
                            <label class="form-label col-md-6">
                                Second answer: <input type="text" name="second" class="form-control" autocomplete="off" required/>
                            </label>
                            <label class="form-label col-md-6">
                                Third answer: <input type="text" name="third" class="form-control" autocomplete="off" required/>
                            </label>
                            <label class="form-label col-md-6">
                                Fourth answer: <input type="text" name="fourth" class="form-control" autocomplete="off" required/>
                            </label>
                        </fieldset>
                        <label>
                            Number of the right answer: <input type="number" name="number" max="4" min="1" autocomplete="off" class="form-control w-50" required/>
                        </label>
                        <br/>
                        <br/>
                        <input type="submit" name="submit" value="add" class="btn btn-success"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>