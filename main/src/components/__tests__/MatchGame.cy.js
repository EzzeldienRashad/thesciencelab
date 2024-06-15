import {ref} from "vue/dist/vue.esm-bundler.js";
import MatchGame from "../MatchGame.vue";

describe("match game", () => {
    beforeEach(() => {
        const props = {
            answeredQuestions: ref(0),
            answered: ref(false), 
            questions: ref([
                {
                    "colA": JSON.stringify(["A is", "B is", "C is"]),
                    "colB": JSON.stringify(["a", "b", "c"])
                },
                {
                    "colA": JSON.stringify(["D is"]),
                    "colB": JSON.stringify(["d"])
                },
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
        cy.mount(MatchGame, {props})
    });
    it("renders questions and answers correctly", function () {
        cy.get("tr").eq(0).should("contain.text", "Column A").should("contain.text", "Column B");
        cy.contains("A is");
        cy.contains("c");
        cy.then(() => this.props.answeredQuestions.value++);
        cy.get("tr td").eq(0).should("have.text", "D is");
        cy.get("tr td").eq(1).should("have.text", "d");
    });
    it("calls functions and performs actions correctly", function () {     
        cy.contains("check").click();
        cy.then(() => {
            expect(this.props.changeAnswered).not.to.have.been.called;
            expect(this.props.addRightAnswer).not.to.have.been.called;
            expect(this.props.changeAnswerIsRight).to.have.been.calledWith("wrong");
        })
    });
});
