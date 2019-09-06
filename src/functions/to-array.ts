/**
 * se il parametro passato non è un array lo trasforma  in array 
 */
export const toArray = (value: any): string[] => Array.isArray(value) ? value : [value];