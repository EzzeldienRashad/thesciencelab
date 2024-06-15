describe("games", () => {
    beforeEach(() => {
        cy.login();
        cy.visit('http://localhost:5174');
        cy.contains("Grade 4").click();
        cy.contains("First Term").click();
    });
    it('choose game control panel works', () => {
        cy.contains("Choose").click();
        cy.getByData("units").find("button").eq(0).click();
        cy.getByData("add-btn").click();
        cy.get("input").eq(0).type("test question");
        cy.get("input").eq(1).type("first answer");
        cy.get("input").eq(2).type("second answer");
        cy.get("input").eq(3).type("third answer");
        cy.get("input").eq(4).type("fourth answer");
        cy.get("input").eq(5).type("1");
        cy.get("input[type=submit]").click();
        cy.contains("Added successfully!");
        cy.get(".modal-body > .btn-danger").click();
        cy.getByData("question").eq(-1).should("contain.text", "test question");
        cy.getByData("question-cont").eq(-1).findByData("choice").eq(3).should("contain", "fourth answer");
        cy.getByData("delete-btn").eq(-1).click();
        cy.getByData("question-cont").eq(-1).should("not.contain.text", "test question");
    });
    it('right or wrong game control panel works', () => {
        cy.contains("Right or wrong").click();
        cy.getByData("units").find("button").eq(0).click();
        cy.getByData("add-btn").click();
        cy.get("input").eq(0).type("Is it right?");
        cy.get("input[type=radio]").check("1");
        cy.get("input[type=submit]").click();
        cy.contains("Added successfully!");
        cy.get(".modal-body > .btn-danger").click();
        cy.getByData("question").eq(-1).should("contain.text", "Is it right?");
        cy.getByData("question").eq(-1).should("have.class", "text-bg-success");
        cy.getByData("delete-btn").eq(-1).click().then(() => {
            cy.getByData("question").eq(-1).should("not.contain.text", "Is it right?");
        });
    });
    it('complete game control panel works', () => {
        cy.contains("Complete").click();
        cy.getByData("units").find("button").eq(0).click();
        cy.getByData("add-btn").click();
        cy.get("input").eq(0).type("This is a ......... sentence");
        cy.get("input").eq(1).type("good");
        cy.get("input").eq(2).type("bad");
        cy.get("input[type=submit]").click();
        cy.contains("Added successfully!");
        cy.get(".modal-body > .btn-danger").click();
        cy.getByData("question").eq(-1).should("contain.text", "This is a");
        cy.getByData("question").eq(-1).should("contain.text", "good");
        cy.getByData("question").eq(-1).should("contain.text", "bad");
        cy.getByData("question").eq(-1).should("contain.text", "sentence");
        cy.getByData("delete-btn").eq(-1).click();
        cy.getByData("question").eq(-1).should("not.contain.text", "This is a");
    });
    it('Match game control panel works', () => {
        cy.contains("Match").click();
        cy.getByData("units").find("button").eq(0).click();
        cy.getByData("add-btn").click();
        cy.get("input").eq(0).type("question 1");
        cy.get("input").eq(1).type("answer 1");
        cy.get("input").eq(2).type("question 2");
        cy.get("input").eq(3).type("answer 2");
        cy.contains("+ question").click();
        cy.contains("- question").click();
        cy.contains("- question").click();
        cy.get("input[type=submit]").click();
        cy.contains("Added successfully!");
        cy.get(".modal-body > .btn-danger").click();
        cy.get("table").eq(-1).find("td").should("have.length", 6);
        cy.get("table").eq(-1).find("td").eq(0).should("contain.text", "question 1");
        cy.get("table").eq(-1).find("td").eq(3).should("contain.text", "answer 2");
        cy.getByData("delete-btn").eq(-1).click();
        cy.get("table").eq(-1).find("td").eq(0).should("not.contain.text", "question 1");
    });
});