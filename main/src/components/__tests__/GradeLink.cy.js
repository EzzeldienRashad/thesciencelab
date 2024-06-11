import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {hashRouter} from "../../router/index.js"
import GradeLink from '../GradeLink.vue'

describe("grade link", () => {
    it('renders', () => {
        cy.mount(GradeLink, {
            props: {
                grade: "my-grade"
            },
            global: {
                components: {
                    FontAwesomeIcon
                },
                plugins: [hashRouter]
            }
        });
        cy.get("button").should("have.text", "My grade")
    })
});
