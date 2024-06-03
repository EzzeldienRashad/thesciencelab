import removeDashes from "../removeDashes";

it("removes dashes and capitalizes the first letter", () => {
    expect(removeDashes("my-grade")).to.equal("My grade");
});