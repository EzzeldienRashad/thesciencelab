<script setup>
const props = defineProps(["label", "inputName", "symbols"]);

function toggleSymbols(event) {
    const symbolsClass = event.currentTarget.parentElement.parentElement.getElementsByClassName('symbols')[0].classList;
    if (symbolsClass.contains("invisible")) {
        symbolsClass.remove("invisible");
    } else {
        setTimeout(() => symbolsClass.add("invisible"), 500);
    }
    symbolsClass.toggle('height-0');
    symbolsClass.toggle('height-300');
}
</script>

<template>
<div>
    <label class="form-label" :for="props.inputName">{{ props.label }}</label>
    <div class="input-group">
        <input type="text" :name="props.inputName" :id="props.inputName" class="form-control" autocomplete="off"/>
        <button type="button" class="input-group-text" @click="$event => toggleSymbols($event)"><font-awesome-icon icon="fa-solid fa-caret-down" /></button>
    </div>
    <div class="symbols border height-0 d-flex flex-wrap align-items-start align-content-start invisible">
        <button type="button" v-for="symbol in props.symbols" :data-symbol="symbol" 
            @click="$event => $event.currentTarget.parentElement.parentElement.getElementsByTagName('INPUT')[0].value += $event.currentTarget.dataset.symbol" 
            class="d-inline-block p-2 fs-5 bg-white border border-black rounded-2 h-auto flex-grow-1 flex-shrink-1">{{ symbol }}</button>
    </div>
</div>
</template>

<style scoped>
.symbols {
    overflow: scroll;
    transition: height 0.5s ease;
}
.height-0 {
    height: 0px;
}
.height-300 {
    height: 150px;
}
</style>