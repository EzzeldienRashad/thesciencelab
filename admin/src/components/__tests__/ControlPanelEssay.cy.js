import {ref, defineComponent} from "vue/dist/vue.esm-bundler.js";
import ControlPanelEssay from "../ControlPanelEssay.vue";

describe("control panel scientific term", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    questions: [
                        {
                            "question": "Give reason?",
                            "answer": "Because",
                            "uploader": "ezzeldien.",
                            "id": 0
                        },
                        {
                            "question": "What happens when?",
                            "answer": "Result.",
                            "uploader": "ezzeldien",
                            "id": 1
                        },
                    ], 
                    msg: ref(undefined), 
                    msgColor: ref(undefined), 
                    deleteQuestion: cy.stub().as("deleteQuestion"), 
                    addQuestion: cy.stub().as("addQuestion"),
                    member: "admin",
                    username: "ezzeldien",
                    uploaders: ["مستخدم 1", "مستخدم 2"],
                    routeParams: {
                        game: "give-reason",
                        grade: "grade-4-first-term"
                    },
                    chosenQuestions: [0],
                    creatingTest: false
                }
                cy.wrap(props).as("props");
                return {props};
            },
            template: `
            <button data-bs-toggle="modal" data-bs-target="#overlay" class="btn btn-success">+ add</button>
            <ControlPanelEssay v-bind="props" />
            `,
            components: {ControlPanelEssay}
        }));
    });
    it("displays available questions", () => {
        cy.getByData("question").should("have.length", 2);
        cy.getByData("question").eq(1).should("contain.text", "What happens when?");
        cy.getByData("question").eq(1).should("contain.text", "Result");
        cy.getByData("question").eq(1).click();
        cy.getByData("uploader-name").eq(1).should("contain.text", "مستخدم 2");
    });
    it("deletes a question", function () {
        cy.getByData("delete-btn").eq(0).click().then(() => {
            expect(this.deleteQuestion).to.have.been.calledOnce;
        });
    });
    it("adds a question", function () {
        cy.contains("add").click();
        cy.contains("Question:").click()
        cy.focused().type("Write the scientific term");
        cy.get("input").eq(1).type("answer")
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