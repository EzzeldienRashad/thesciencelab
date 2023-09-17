import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {hashRouter} from "../../router/index.js"
import GradeLink from '../GradeLink.vue'

describe('<GradeLink />', () => {
    it('renders', () => {
        cy.mount(GradeLink, 
            {grade: "my-grade"},
            {documentWidth: Cypress.config("viewportWidth")},
            {FontAwesomeIcon},
            [hashRouter]
        )
        cy.get("a").should("have.text", "My grade ")
    })
})