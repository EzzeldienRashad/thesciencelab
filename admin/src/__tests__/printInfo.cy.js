describe("print info", () => {
    context("prints lessons correctly", () => {
        it("returns the lesson's questions", function () {
            cy.request("http://localhost/info/functions/printInfo.php?grade=grade-4-first-term&game=choose&unit=1.1-Adaptation-and-Survival").then(resp => {
                resp = JSON.parse(resp.body);
                expect(resp).to.be.instanceOf(Object);
                resp = Object.values(resp);
                expect(resp).to.be.instanceOf(Array).and.not.have.length(0);
                expect(resp[0][0]).to.be.a("string");
                expect(resp[0][1]).to.have.instanceOf(Array).and.have.length(4);
                expect(resp[0][2]).to.be.a("number").and.be.at.most(3);
            });
        });
    });
    context("handles edge cases correctly", () => {
        it("returns all questions when unit = whole", () => {
            cy.request("http://localhost/info/functions/printInfo.php?grade=grade-4-first-term&game=choose&unit=whole").then(resp => {
                expect(Object.values(JSON.parse(resp.body))).to.be.instanceOf(Array).and.not.have.length(0);
            });
        });
    });
});