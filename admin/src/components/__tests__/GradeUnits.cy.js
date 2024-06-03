import {hashRouter} from "../../router";
import GradeUnits from "../GradeUnits.vue";
import vueRouter from "@/modules/vue-router";

it("shows available units", () => {
    cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=my-grade&game=my-game", ["unit-1", "unit-2", "unit-3"]);
    cy.stub(vueRouter, "useRoute").returns({
        params: {
            grade: "my-grade",
            game: "my-game"
        }
    });
    cy.mount(GradeUnits, {
        props: {
            onSetUnit: cy.stub().as("setUnit")
        },
        global: {
            plugins: [hashRouter]
        }
    });
    cy.contains("Unit 2").click().then(() => {
        cy.get("@setUnit").should("have.been.calledWith", "unit-2")
    });
});