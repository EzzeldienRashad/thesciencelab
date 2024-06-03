describe("prints grades' information correctly", () => {
    it("returns available grades", () => {
        cy.request("http://localhost/info/functions/printInfo.php").then(resp => {
            expect(JSON.parse(resp.body)).to.include("grade-5-second-term");
        });
    });
    it("returns available games", () => {
        cy.request("http://localhost/info/functions/printInfo.php?grade=grade-5-second-term").then(resp => {
            expect(JSON.parse(resp.body)).to.have.members(["choose", "right-or-wrong", "complete", "match"]);
        });
    });
    it("returns available lessons", () => {
        cy.request("http://localhost/info/functions/printInfo.php?grade=grade-5-second-term&game=choose").then(resp => {
            expect(JSON.parse(resp.body)).to.be.instanceOf(Array).and.not.have.length(0);
            cy.wrap(JSON.parse(resp.body)[0]).as("firstLesson");
        });
    });
    it("returns the lesson's questions", function () {
        cy.request("http://localhost/info/functions/printInfo.php?grade=grade-5-second-term&game=choose&unit=" + this.firstLesson).then(resp => {
            resp = JSON.parse(resp.body);
            expect(resp).to.be.instanceOf(Array).and.not.have.length(0);
            expect(resp[0][0]).to.be.a("string");
            expect(resp[0][1]).to.have.instanceOf(Array).and.have.length(4);
            expect(resp[0][2]).to.be.a("number").and.be.at.most(3);
        });
    });
});
describe("handles edge cases correctly", () => {
    it("reutrns empty array on wrong information", () => {
        cy.request("http://localhost/info/functions/printInfo.php?grade=does-not-exist").then(resp => {
            expect(JSON.parse(resp.body)).to.have.length(0);
        });
    });
    it("returns all questions when unit = whole", () => {
        cy.request("http://localhost/info/functions/printInfo.php?grade=grade-5-second-term&game=choose&unit=whole").then(resp => {
            expect(JSON.parse(resp.body)).to.be.instanceOf(Array).and.not.have.length(0);
        });
    });
});