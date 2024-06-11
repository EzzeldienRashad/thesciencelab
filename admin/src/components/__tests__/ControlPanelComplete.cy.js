import {ref, defineComponent} from "vue/dist/vue.esm-bundler.js";
import ControlPanelComplete from "../ControlPanelComplete.vue";

describe("control panel complete", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    questions: [
                        ["I", ["love", "hate"], "science."],
                        ["", ["Banana", "Apple"], "is banana."],
                        ["Ant is", ["ant", "arm"], ""]
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
            <ControlPanelComplete v-bind="props" />
            `,
            components: {ControlPanelComplete}
        }));
    });
    it("displays available questions", () => {
        cy.getByData("question").should("have.length", 3);
        cy.getByData("question").eq(0).should("contain.text", "I");
        cy.getByData("question").eq(0).should("contain.text", "science");
        cy.getByData("question").eq(0).find("span").eq(1).should("contain.text", "love");
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
        cy.get("input").eq(1).type("right")
        cy.get("input").eq(2).type("wrong")
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