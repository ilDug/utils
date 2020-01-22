

export class User {

    constructor(u: Partial<User>) {
        Object.assign(this, u)
    }
    
    public firstName:string;


    public familyName: string;


    public email: string;


    get displayName(): string {
        return `${this.firstName} ${this.familyName}`
    }

}