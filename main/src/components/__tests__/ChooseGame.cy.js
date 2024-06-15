import {defineComponent, ref} from "vue/dist/vue.esm-bundler.js";
import ChooseGame from "../ChooseGame.vue";

describe("choose game", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    answeredQuestions: ref(0),
                    answered: ref(false), 
                    questions: ref([
                        {
                            "question": "What is a?",
                            "choiceA": "a",
                            "choiceB": "b",
                            "choiceC": "c",
                            "choiceD": "d",
                            "answer": 1,
                            "id": 0
                        },
                        {
                            "question": "What is b?",
                            "choiceA": "a",
                            "choiceB": "b",
                            "choiceC": "c",
                            "choiceD": "d",
                            "answer": 2,
                            "id": 1
                        }
                    ]), 
                    changeAnswerIsRight() {},
                    addRightAnswer() {},
                    changeAnswered() {
                        props.answered.value = true;
                    }
                }
                cy.wrap(props).as("props")
                for (let key of Object.keys(props)) {
                    if (typeof props[key] == "function") cy.spy(props, key)
                }
                return {props}
            },
            template: `
                <main>
                    <div class="question">
                        <ChooseGame v-bind="props"/>
                    </div>
                </main>
            `,
            components: {ChooseGame}
        }))
    });
    it("renders questions and answers correctly", function () {
        cy.getByData("question").should("have.text", "What is a?");
        cy.getByData("choices").find("button").should("have.length", 4);
        cy.getByData("choices").find("button").eq(1).should("have.text", "b");
        cy.then(() => this.props.answeredQuestions.value++);
        cy.getByData("question").should("have.text", "What is b?");
    });
    it("calls functions and performs actions correctly", function () {        
        cy.getByData("choices").find(" button").eq(0).click().then(() => {
            expect(this.props.changeAnswered).to.have.been.called;
            expect(this.props.addRightAnswer).to.have.been.called
        });
        cy.getByData("choices").find(" button").eq(1).should("have.css", "pointer-events", "none");
        cy.then(() => {
            this.props.answeredQuestions.value++;
            this.props.answered.value = false;
        });
        cy.getByData("choices").find(" button").eq(2).click().then(() => {
            expect(this.props.changeAnswerIsRight).to.have.been.calledWith("wrong")
        });
    });
});
