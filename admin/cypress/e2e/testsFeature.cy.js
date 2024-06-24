describe("games", () => {
    it('Tests feature works', () => {
        cy.login();
        cy.visit('http://localhost:5174');
        cy.contains("Grade 4").click();
        cy.contains("First Term").click();
        cy.contains("Choose").click();
        cy.getByData("units").find("button").eq(0).click();
        cy.getByData("createTest").click();
        cy.getByData("question").eq(-1).click();
        cy.getByData("question").eq(-2).click();
        cy.getByData("question").eq(-3).click();
        cy.getByData("startTest").click();
        cy.getByData("testCode").type("testcode");
        cy.getByData("testValidFor").type("1");
        cy.getByData("testSubmit").click();
        cy.getByData("testModalDismiss").click();
        cy.visit('http://localhost:5173');
        cy.contains("Grade 4").click();
        cy.contains("First Term").click();
        cy.contains("Choose").click();
        cy.getByData("deviceName").type("name")
        cy.getByData("testCode").type("testcode")
        cy.getByData("testSubmit").click();
        cy.getByData("question")
        cy.visit("http://localhost:5174");
        cy.getByData("runningTests").click();
        cy.getByData("testcode").findByData("deleteTest").click();
        cy.getByData("testcode").should("not.exist")
    });
});