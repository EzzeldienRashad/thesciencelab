import { mount } from 'cypress/vue'

Cypress.Commands.add('mount', mount)
Cypress.Commands.add("getByData", selector => cy.get(`[data-cy=${selector}]`));