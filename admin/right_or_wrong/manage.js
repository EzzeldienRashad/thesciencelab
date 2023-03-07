let url = new URL(location.href);
let searchUrl = url.searchParams;
if (searchUrl.has("scroll")) {
    scrollTo(0, searchUrl.get("scroll"));
}
async function deleteQuestion(element) {
    await fetch("delete.php?grade=" + searchUrl.get("grade") +
    "&unit=" + searchUrl.get("unit") + "&question=" + element.dataset.question);
    searchUrl.set("scroll", scrollY);
    window.location.href = url.href;
}