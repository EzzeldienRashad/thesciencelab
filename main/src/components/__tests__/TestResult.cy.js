import { hashRouter } from "../../router";
import vueRouter from "@/modules/vue-router";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import TestResult from "../TestResult.vue";

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
    cy.getByData("score").should("have.text", "1 / 10");
    cy.getByData("score-text").should("have.text", "very bad");
});
it("displays 'fair'", () => {
    mountTestResult(5);
    cy.getByData("score").should("have.text", "5 / 10");
    cy.getByData("score-text").should("have.text", "fair");
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