import { hashRouter } from "../../router";
import vueRouter from "@/modules/vue-router";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import GameSettings from "../GameSettings.vue";

describe("game settings", () => {
    beforeEach(() => {
        cy.stub(vueRouter, "useRoute").returns({
            params: {
                grade: "my-grade",
                game: "my-game"
            }
        });
    });
    it("displays units and test type", () => {
        cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=my-grade&game=my-game", ["unit1", "unit2", "unit3"]);
        cy.intercept("http://127.0.0.1/info/functions/printInfo.php?grade=my-grade&game=my-game&unit=whole", [
            ["What is a?", ["a", "b", "c", "d"], 1],
            ["What is b?", ["a", "b", "c", "d"], 2],
        ]);
        cy.mount(GameSettings, {
            global: {
                components: {FontAwesomeIcon},
                plugins: [hashRouter],
            }
        });
        cy.getByData("units").find("button").eq(0).should("have.text", "Unit1");
        cy.contains("The whole term").click();
        cy.contains("easy");
        cy.contains("medium");
        cy.contains("hard");
    });
});
