import { hashRouter } from "../../router";
import vueRouter from "@/modules/vue-router";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import TestResult from "../TestResult.vue";

describe("<TestResult />", () => {
    beforeEach(() => {
        cy.stub(vueRouter, "useRoute").returns({
            params: {
                grade: "my-grade",
                game: "my-game"
            }
        });
    });
    it("displays 'very bad'", () => {
        mountTestResult(1);
    });
});
function mountTestResult(score) {
    cy.mount(TestResult, {
        props: {
            score,
            total: 10
        },
        global: {
            components: {
                FontAwesomeIcon
            },
            plugins: [hashRouter],
        },
    });
}