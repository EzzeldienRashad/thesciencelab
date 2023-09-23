describe("router works", () => {
    it("renders main pages", () => {
        cy.visit("http://localhost:5173/");
        cy.contains("The Science Lab");
        cy.getByData("grades").find("a").its("length").should("be.at.least", 12);
        cy.get("li:nth-child(2) > .nav-link").click();
        cy.url().should("contain", "/about");
    })
    it("renders main pages on small devices", () => {
        cy.viewport(360, 600);
        cy.visit("http://localhost:5173/");
        cy.contains("The Science Lab");
        cy.get("li:nth-child(2) > .nav-link").should("be.hidden");
        cy.get(".navbar-toggler-icon").click();
        cy.get("li:nth-child(2) > .nav-link").click();
        cy.url().should("contain", "/about");
    })
})