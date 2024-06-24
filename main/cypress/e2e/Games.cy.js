describe("games", () => {
    beforeEach(() => {
        cy.visit('http://localhost:5173');
        cy.contains("Grade 4").click();
        cy.contains("First Term").click();
    });
    it.only("Choose game works", () => {
        cy.contains("Choose").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=choose&unit=" + btn[0].dataset.unit, [
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
                },
                {
                    "question": "What is c?",
                    "choiceA": "a",
                    "choiceB": "b",
                    "choiceC": "c",
                    "choiceD": "d",
                    "answer": 1,
                    "id": 2
                }
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("mixed").click();
        cy.getByData("choices").find("button").should("have.length", 4);
        cy.getByData("choices").find("button").eq(0).click();
        cy.get("#next-arrow").click();
        cy.getByData("choices").find("button").eq(0).click();
        cy.get("#next-arrow").click();
        cy.getByData("score").its(0).its("textContent").its(0).then(num => expect(Number(num)).to.be.at.least(1));
    });
    it("Right Or Wrong game works", () => {
        cy.contains("Right or wrong").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=right-or-wrong&unit=" + btn[0].dataset.unit, [
                {
                    "question": "Is it right?",
                    "answer": 1,
                    "id": 0,
                    "level": "easy"
                },
                {
                    "question": "Is it wrong?",
                    "answer": 0,
                    "id": 1,
                    "level": "easy"
                },
                {
                    "question": "Is it wrong?",
                    "answer": 0,
                    "id": 1,
                    "level": "hard"
                }
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("easy").click();
        cy.getByData("choices").find("button").should("have.length", 2);
        cy.getByData("choices").find("button").eq(0).click();
        cy.get("#next-arrow").click();
        cy.getByData("choices").find("button").eq(0).click();
        cy.get("#next-arrow").click();
        cy.getByData("score").its(0).its("textContent").its(0).should("equal", "1");
    });
    it("Complete game works", () => {
        cy.contains("Complete").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=complete&unit=" + btn[0].dataset.unit, [
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
                },
                {
                    "part1": "Ant is",
                    "rightAnswer": "ant",
                    "wrongAnswer": "arm",
                    "part2": "",
                    "id": 2
                },
                {
                    "part1": "I",
                    "rightAnswer": "love",
                    "wrongAnswer": "hate",
                    "part2": "science",
                    "id": 3
                },
                {
                    "part1": "",
                    "rightAnswer": "Banana",
                    "wrongAnswer": "Apple",
                    "part2": "is banana.",
                    "id": 4
                },
                {
                    "part1": "Ant is",
                    "rightAnswer": "ant",
                    "wrongAnswer": "arm",
                    "part2": "",
                    "id": 5
                },
                {
                    "part1": "I",
                    "rightAnswer": "love",
                    "wrongAnswer": "hate",
                    "part2": "science",
                    "id": 6
                },
                {
                    "part1": "",
                    "rightAnswer": "Banana",
                    "wrongAnswer": "Apple",
                    "part2": "is banana.",
                    "id": 7
                },
                {
                    "part1": "Ant is",
                    "rightAnswer": "ant",
                    "wrongAnswer": "arm",
                    "part2": "",
                    "id": 8
                },
                {
                    "part1": "Ant is",
                    "rightAnswer": "ant",
                    "wrongAnswer": "arm",
                    "part2": "",
                    "id": 9
                },
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("mixed").click();
        cy.get("input").should("have.length", 5);
    });
    it("Match game works", () => {
        cy.contains("Match").click();
        cy.getByData("units").find("button").its("length").should("be.at.least", 2);
        cy.getByData("units").find("button").eq(0).then(btn => {
            cy.intercept("http://127.0.0.1/thesciencelab/info/functions/printInfo.php?grade=grade-4-first-term&game=match&unit=" + btn[0].dataset.unit, [
                {
                    "colA": JSON.stringify(["A is", "B is", "C is"]),
                    "colB": JSON.stringify(["a", "b", "c"])
                },
                {
                    "colA": JSON.stringify(["D is"]),
                    "colB": JSON.stringify(["d"])
                },
            ]);
        });
        cy.getByData("units").find("button").eq(0).click();
        cy.contains("mixed").click();
        cy.get("table").contains("Column A");
        cy.get("table").contains("Column B");
    });
});