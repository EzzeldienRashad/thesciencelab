import {ref, defineComponent} from "vue/dist/vue.esm-bundler.js";
import ControlPanelChoose from "../ControlPanelChoose.vue";

describe("control panel choose", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    questions: [
                        ["What is a?", ["a", "b", "c", "d"], 1],
                        ["What is b?", ["a", "b", "c", "d"], 2],
                    ], 
                    msg: ref(undefined), 
                    msgColor: ref(undefined), 
                    deleteQuestion: cy.stub(), 
                    addQuestion: cy.stub(),
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
            <ControlPanelChoose v-bind="props" />
            `,
            components: {ControlPanelChoose}
        }));
    });
    it("displays available questions", () => {
        cy.getByData("question").should("have.length", 2);
        cy.getByData("question").eq(0).should("contain.text", "What is a?");
        cy.getByData("question-cont").eq(0).findByData("answer").should("contain.text", "abcd")
    });
    it("deletes a question", function () {
        cy.getByData("delete-btn").eq(0).click().then(() => {
            expect(this.props.deleteQuestion).to.have.been.calledOnce;
        });
    });
    it("adds a question", function () {
        cy.contains("add").click();
        cy.contains("Question:").click()
        cy.focused().type("q1");
        cy.getByData("choices").find("input").eq(0).type("a1");
        cy.getByData("choices").find("input").eq(1).type("a2");
        cy.getByData("choices").find("input").eq(2).type("a3");
        cy.getByData("choices").find("input").eq(3).type("a4");
        cy.getByData("right-answer-num").type(1);
        cy.getByData("submit").click().then(() => {
            expect(this.props.addQuestion).to.have.been.calledOnce;
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