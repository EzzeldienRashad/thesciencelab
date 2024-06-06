import password from "/password.js";

it("prints logout if logged out", () => {
    cy.request("http://localhost/info/functions/add.php").then(resp => {
        expect(resp.body).to.contain("logout");
    });
});

describe("handles error cases correctly", () => {
    beforeEach(() => {
        cy.request("POST", "http://localhost/info/functions/login.php", {
            username: "ezzeldienrashad",
            password
        })
    });
    it("does nothing if info are not enough", () => {
        cy.request("http://localhost/info/functions/add.php").then(resp => {
            expect(resp.allRequestResponses[0]["Response Body"]).to.have.length(0);
        });
    });
});