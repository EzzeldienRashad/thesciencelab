import password from "/password.js";

describe("add.php handles error cases correctly", () => {
    context("not logged in", () => {
        it("prints logout if logged out", () => {
            cy.request("http://localhost/info/functions/add.php").then(resp => {
                expect(resp.body).to.contain("logout");
            });
        });
    });
    context("logged in", () => {
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
});