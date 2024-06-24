describe("print info", () => {
    context("prints lessons correctly", () => {
        it("returns the lesson's questions", function () {
            cy.request("http://localhost/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=choose&unit=1.1-Adaptation-and-Survival").then(resp => {
                resp = JSON.parse(resp.body);
                expect(resp).to.be.instanceOf(Object);
                expect(resp[0]["question"]).to.be.a("string");
                expect(resp[0]["choiceA"]).to.be.a("string");
                expect(resp[0]["answer"]).to.be.a("number").and.be.at.most(3);
            });
            cy.request("http://localhost/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=complete&unit=1.1-Adaptation-and-Survival").then(resp => {
                resp = JSON.parse(resp.body);
                expect(resp).to.be.instanceOf(Object);
                expect(resp[0]["wrongAnswer"]).to.be.a("string");
                expect(resp[0]["rightAnswer"]).to.be.a("string");
            });
            cy.request("http://localhost/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=match&unit=1.1-Adaptation-and-Survival").then(resp => {
                resp = JSON.parse(resp.body);
                expect(resp).to.be.instanceOf(Object);
                expect(JSON.parse(resp[0]["colA"])).to.be.instanceOf(Array);
                expect(JSON.parse(resp[0]["colB"])).to.be.instanceOf(Array);
                expect(JSON.parse(resp[0]["colA"])).to.have.lengthOf(JSON.parse(resp[0]["colA"]).length)
            });
            cy.request("http://localhost/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=right-or-wrong&unit=1.1-Adaptation-and-Survival").then(resp => {
                resp = JSON.parse(resp.body);
                expect(resp).to.be.instanceOf(Object);
                expect(resp[0]["question"]).to.be.a("string");
                expect(resp[0]["answer"]).to.be.a("number").and.be.at.most(1);
            });
        });
    });
    context("handles edge cases correctly", () => {
        it("returns all questions when unit = whole", () => {
            cy.request("http://localhost/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=choose&unit=whole").then(resp => {
                expect(Object.values(JSON.parse(resp.body))).to.be.instanceOf(Array).and.not.have.length(0);
            });
        });
    });
});