import removeDashes from "../removeDashes";

describe("remove dashes", () => {
    it("removes dashes and capitalizes the first letter", () => {
        expect(removeDashes("my-grade")).to.equal("My grade");
    });
});