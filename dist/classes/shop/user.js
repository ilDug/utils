var User = /** @class */ (function () {
    function User(u) {
        Object.assign(this, u);
    }
    Object.defineProperty(User.prototype, "displayName", {
        get: function () {
            return this.firstName + " " + this.familyName;
        },
        enumerable: true,
        configurable: true
    });
    return User;
}());
export { User };
