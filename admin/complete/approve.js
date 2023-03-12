let url = new URL(location.href);
let searchUrl = url.searchParams;
if (searchUrl.has("scroll")) {
    scrollTo(0, searchUrl.get("scroll"));
}
async function deleteQuestion(element) {
    await fetch("delete.php?grade=" + element.dataset.questionGrade +
    "&unit=" + element.dataset.questionLesson + "&questionnum=" + element.dataset.questionNum + "&approval=true");
    searchUrl.set("scroll", scrollY);
    window.location.href = url.href;
}
async function add(element) {
    await fetch("add.php?sender=js&grade=" + element.dataset.questionGrade + 
    "&unit=" + element.dataset.questionLesson, {
        method: "post",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: element.dataset.question
    });
    deleteQuestion(element);
}