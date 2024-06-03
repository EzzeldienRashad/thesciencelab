import { mount } from 'cypress/vue'
import password from "/password.js";

Cypress.Commands.add('mount', mount)
Cypress.Commands.add("getByData", selector => cy.get(`[data-cy=${selector}]`));
Cypress.Commands.add("findByData", {prevSubject: true}, (subject, selector) => cy.wrap(subject).find(`[data-cy=${selector}]`));
Cypress.Commands.add("login", () => {
    cy.session(password, () => {
        cy.visit("http://localhost:5174");
        cy.getByData("password").type(password);
        cy.contains("login").click();
    });
});