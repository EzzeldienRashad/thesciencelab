let url = new URL(location.href);
let searchUrl = url.searchParams;
if (searchUrl.has("scroll")) {
    scrollTo(0, searchUrl.get("scroll"));
}
async function deleteQuestion(element) {
    await fetch("delete.php?grade=" + searchUrl.get("grade") +
    "&unit=" + searchUrl.get("unit") + "&questionnum=" + element.dataset.questionNum);
    searchUrl.set("scroll", scrollY);
    location.href = url.href;
}