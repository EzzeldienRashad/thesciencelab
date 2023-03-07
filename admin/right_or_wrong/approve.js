let url = new URL(location.href);
let searchUrl = url.searchParams;
if (searchUrl.has("scroll")) {
    scrollTo(0, searchUrl.get("scroll"));
}
async function deleteQuestion(element) {
    await fetch("delete.php?grade=" + element.dataset.questionGrade +
    "&unit=" + element.dataset.questionLesson + "&question=" + element.dataset.question + "&approval=true");
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
        body: JSON.stringify({
            "submit": "submit", 
            "question": element.dataset.question,
            "answer": element.dataset.questionRight})
    });
    deleteQuestion(element);
}