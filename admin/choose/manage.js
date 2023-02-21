async function deleteQuestion(element) {
    let url = new URL(location).searchParams;
    await fetch("delete.php?grade=" + url.get("grade") +
    "&unit=" + url.get("unit") + "&questionnum=" + element.dataset.questionNum);
    location.reload();
}