export interface IBaseAddress {
    /** via e numero */
    address: string;
    city: string;
    cap: string;
    province: string;
    country?: string;
}
export interface IShippingAddress extends IBaseAddress {
    firstName: string;
    lastName: string;
    companyName?: string;
    phone?: string;
}
export interface IBillingAddress extends IShippingAddress {
    email: string;
    CF?: string;
    PIva?: string;
}
