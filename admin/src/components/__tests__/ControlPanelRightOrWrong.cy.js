import {ref, defineComponent} from "vue/dist/vue.esm-bundler.js";
import ControlPanelRightOrWrong from "../ControlPanelRightOrWrong.vue";

describe("control panel right or wrong", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    questions: {
                        0: ["Is it right?", 1],
                        1: ["Is it wrong?", 0]
                    }, 
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
            <ControlPanelRightOrWrong v-bind="props" />
            `,
            components: {ControlPanelRightOrWrong}
        }));
    });
    it("displays available questions", () => {
        cy.getByData("question").should("have.length", 2);
        cy.getByData("question").eq(0).should("contain.text", "Is it right?");
    });
    it("deletes a question", function () {
        cy.getByData("delete-btn").eq(1).click().then(() => {
            expect(this.deleteQuestion).to.have.been.calledOnceWith("1");
        });
    });
    it("adds a question", function () {
        cy.contains("add").click();
        cy.contains("Question:").click()
        cy.focused().type("wrong");
        cy.get("input[type=radio]").eq(1).check();
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