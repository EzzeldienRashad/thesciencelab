import {defineComponent, ref} from "vue/dist/vue.esm-bundler.js";
import RightOrWrongGame from "../RightOrWrongGame.vue";

describe("right or wrong game", () => {
    beforeEach(() => {
        cy.mount(defineComponent({
            setup() {
                const props = {
                    answeredQuestions: ref(0),
                    answered: ref(false), 
                    questions: ref([
                        {
                            "question": "Is it right?",
                            "answer": 1,
                            "id": 0
                        },
                        {
                            "question": "Is it wrong?",
                            "answer": 0,
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
                        <RightOrWrongGame v-bind="props"/>
                    </div>
                </main>
            `,
            components: {RightOrWrongGame}
        }))
    });
    it("renders questions and answers correctly", function () {
        cy.getByData("question").should("have.text", "Is it right?");
        cy.getByData("choices").find(" button img").should("have.length", 2);
        cy.then(() => this.props.answeredQuestions.value++);
        cy.getByData("question").should("have.text", "Is it wrong?");
    });
    it("calls functions and performs actions correctly", function () {        
        cy.getByData("choices").find(" button").eq(0).click().then((button) => {
            expect(this.props.changeAnswered).to.have.been.called;
            expect(this.props.addRightAnswer).to.have.been.called;
            expect(button).to.have.css("pointer-events", "none");
        });
        cy.getByData("question").should("have.class", "text-success");
        cy.then(() => {
            this.props.answeredQuestions.value++;
            this.props.answered.value = false;
        });
        cy.getByData("choices").find(" button").eq(0).click().then(() => {
            expect(this.props.changeAnswerIsRight).to.have.been.calledWith("wrong")
        });
    });
});
