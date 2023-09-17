import {ref} from "vue/dist/vue.esm-bundler.js";
import ChooseQuestions from "../ChooseQuestions.vue";

describe("<ChooseQuestions />", () => {
    it("renders", () => {
        cy.mount(ChooseQuestions, {
            props: {
                answeredQuestions: ref(0),
                answered: ref(false), 
                questions: ref([
                    ["What is a?", ["a", "b", "c", "d"], 1],
                    ["What is b?", ["a", "b", "c", "d"], 2],
                ]),
                changeAnswerIsRight: cy.stub(),
                addRightAnswer: cy.stub(),
                changeAnswered: cy.stub()
            }
        })
    });
});

/*
        cy.mount(defineComponent({
            setup() {
                const inheritedVariables = {
                    answeredQuestions: ref(0),
                    answered: ref(false), 
                    questions: ref([
                        ["What is a?", ["a", "b", "c", "d"], 1],
                        ["What is b?", ["a", "b", "c", "d"], 2],
                    ]),
                    changeAnswerIsRight: cy.stub(),
                    addRightAnswer: cy.stub(),
                    changeAnswered: cy.stub()
                };
                return {
                    inheritedVariables,
                    rightSound,
                    wrongSound,
                }                
            },
            components: {ChooseQuestions},
            template: `
                <ChooseQuestions v-bind="inheritedVariables" />
            `
        }))
*/