import {ref} from "vue/dist/vue.esm-bundler.js";
import CompleteGame from "../CompleteGame.vue";

describe("complete game", () => {
    beforeEach(() => {
        const props = {
            answeredQuestions: ref(0),
            answered: ref(false), 
            questions: ref(
                [
                    [
                        {
                            "part1": "I",
                            "rightAnswer": "love",
                            "wrongAnswer": "hate",
                            "part2": "science",
                            "id": 0
                        },
                        {
                            "part1": "",
                            "rightAnswer": "Banana",
                            "wrongAnswer": "Apple",
                            "part2": "is banana.",
                            "id": 1
                        }
                    ],
                    [
                        {
                            "part1": "Ant is",
                            "rightAnswer": "ant",
                            "wrongAnswer": "arm",
                            "part2": "",
                            "id": 2
                        }
                    ],
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
        cy.mount(CompleteGame, {props})
    });
    it("renders questions and answers correctly", function () {
        cy.getByData("choices")
            .should("contain.text", "Apple")
            .should("contain.text", "Banana")
            .should("contain.text", "love")
            .should("contain.text", "hate");
        cy.getByData("questions").find(" input").should("have.length", 2);
        cy.getByData("questions")
            .should("contain.text", "I")
            .should("contain.text", "science")
            .should("contain.text", "is banana");
        cy.then(() => this.props.answeredQuestions.value++);
        cy.getByData("choices")
            .should("contain.text", "arm")
            .should("contain.text", "ant")
    });
    it("calls functions and performs actions correctly", function () {     
        cy.getByData("questions").find(" input").eq(0).type("love");   
        cy.getByData("questions").find(" input").eq(1).type("apple");
        cy.contains("check").click().then(() => {
            expect(this.props.changeAnswered).not.to.have.been.called;
            expect(this.props.addRightAnswer).to.have.been.calledOnce;
            expect(this.props.changeAnswerIsRight).to.have.been.calledWith("wrong");
        });  
        cy.getByData("questions").find(" input").eq(1).clear().type("BaNanA");
        cy.contains("check").click().then(() => {
            expect(this.props.changeAnswered).to.have.been.called;
            expect(this.props.addRightAnswer).to.have.been.called;
            expect(this.props.changeAnswerIsRight).to.have.been.calledWith("right");
        });  
    });
});
