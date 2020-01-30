export declare class User {
    constructor(u: Partial<User>);
    firstName: string;
    familyName: string;
    email: string;
    get displayName(): string;
}
