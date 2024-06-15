describe("games", () => {
    beforeEach(() => {
        cy.visit('http://localhost:5173');
        cy.contains("Grade 4").click();
        cy.contains("First Term").click();
    });
    it("Choose game works", () => {
        cy.contains("Choose").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=grade-4-first-term&game=choose&unit=" + btn[0].dataset.unit, [
                ["What is a?", ["a", "b", "c", "d"], 1],
                ["What is b?", ["a", "b", "c", "d"], 2],
                ["What is c?", ["a", "b", "c", "d"], 3],
                ["What is a2?", ["a", "b", "c", "d"], 1],
                ["What is b2?", ["a", "b", "c", "d"], 2],
                ["What is c2?", ["a", "b", "c", "d"], 3],
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("easy").click();
        cy.getByData("choices").find("button").should("have.length", 4);
        cy.getByData("choices").find("button").eq(2).click();
        cy.get("#next-arrow").click();
        cy.wait(500);
        cy.getByData("question").then(question => {
            cy.getByData("choices").contains(question[0].textContent[8]).click();
        });
        cy.get("#next-arrow").click();
        cy.getByData("score").its(0).its("textContent").its(0).then(num => expect(Number(num)).to.be.at.least(1));
    });
    it("Right Or Wrong game works", () => {
        cy.contains("Right or wrong").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(1).then(btn => {
            cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=grade-4-first-term&game=right-or-wrong&unit=" + btn[0].dataset.unit, {
                0: ["Is it right?", 1]
            });
        });
        cy.getByData("units").find("button").eq(1).click();
        cy.contains("hard").click();
        cy.getByData("choices").find("button").should("have.length", 2);
        cy.getByData("choices").find("button").eq(0).click();
        cy.get("#next-arrow").click();
        cy.getByData("score").its(0).its("textContent").its(0).should("equal", "1");
    });
    it("Complete game works", () => {
        cy.contains("Complete").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=grade-4-first-term&game=complete&unit=" + btn[0].dataset.unit, [
                ["A is ", ["a", "b"], ""],
                ["", ["A", "B"], " is b"],
                ["A is ", ["a", "b"], ""],
                ["", ["A", "B"], " is b"],
                ["The letter ", ["a", "b"], " is a"]
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("hard").click();
        cy.get("input").should("have.length", 5);
    });
    it("Match game works", () => {
        cy.contains("Match").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=grade-4-first-term&game=match&unit=" + btn[0].dataset.unit, [
                {
                    "Question 1 is":"answer 1",
                    "Question 2 is":"answer 2",
                    "Question 3 is":"answer 3"
                },
    
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("hard").click();
        cy.get("table").contains("Column A");
        cy.get("table").contains("Column B");
    });
});