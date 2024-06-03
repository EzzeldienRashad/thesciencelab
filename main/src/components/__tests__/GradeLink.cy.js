import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {hashRouter} from "../../router/index.js"
import GradeLink from '../GradeLink.vue'

it('renders', () => {
    cy.mount(GradeLink, {
        props: {
            grade: "my-grade"
        },
        global: {
            provide: {
                documentWidth: Cypress.config("viewportWidth")
            },
            components: {
                FontAwesomeIcon
            },
            plugins: [hashRouter]
        }
    });
    cy.get("a").should("have.text", "My grade ")
})
