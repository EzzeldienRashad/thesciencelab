import {ref, defineComponent} from "vue/dist/vue.esm-bundler.js";
import ControlPanelMatch from "../ControlPanelMatch.vue";

describe("control panel match", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    questions: [
                        {
                            "A is": "a",
                            "B is": "b",
                            "C is": "c"
                        },
                        {
                            "D is": "d"
                        }
                    ], 
                    msg: ref(undefined), 
                    msgColor: ref(undefined), 
                    deleteQuestion: cy.stub().as("deleteQuestion"), 
                    addQuestion: cy.stub().as("addQuestion"),
                    member: "admin",
                    uploaders: ["مستخدم 1", "مستخدم 2"],
                    routeParams: {
                        game: "choose",
                        grade: "grade-4-first-term"
                    }
                }
                cy.wrap(props).as("props");
                return {props};
            },
            template: `
            <button data-bs-toggle="modal" data-bs-target="#overlay" class="btn btn-success">+ add</button>
            <ControlPanelMatch v-bind="props" />
            `,
            components: {ControlPanelMatch}
        }));
    });
    it("displays available questions", () => {
        cy.get("table").should("have.length", 2);
        cy.get("tr td").eq(0).should("contain.text", "A is");
        cy.get("tr td").eq(1).should("contain.text", "a");
    });
    it("deletes a question", function () {
        cy.getByData("delete-btn").eq(0).click().then(() => {
            expect(this.deleteQuestion).to.have.been.calledOnce;
        });
    });
    it("adds a question", function () {
        cy.contains("add").click();
        cy.contains("Question:").click()
        cy.focused().type("q1");
        cy.contains("Answer:").click()
        cy.focused().type("a1");
        cy.get("input").eq(2).type("q2");
        cy.get("input").eq(3).type("a2");
        cy.get("input").its("length").as("inputsNum");
        cy.contains("+ question").click().then(function () {
            cy.get("input").its("length").should("be.equal", this.inputsNum + 2);
        });
        cy.contains("- question").click();
        cy.contains("- question").click();
        cy.then(function () {
            cy.get("input").its("length").should("be.equal", this.inputsNum - 2);
        });;
        cy.getByData("submit").click().then(() => {
            expect(this.addQuestion).to.have.been.calledOnce;
        });
        cy.get("[data-bs-dismiss=modal]").click();
    });
    it("displays a message on error or success", function () {
        cy.contains("add").click();
        cy.then(() => {
            this.props.msg.value = "error";
        });
        cy.get(".alert").should("have.class", "alert-primary");
        cy.get(".alert").should("have.text", "error");
        cy.then(() => {
            this.props.msgColor.value = "danger";
        });
        cy.get(".alert").should("have.class", "alert-danger");
        cy.get("[data-bs-dismiss=modal]").click();
    });
});