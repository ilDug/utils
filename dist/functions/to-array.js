/**
 * se il parametro passato non è un array lo trasforma  in array
 */
export var toArray = function (value) { return Array.isArray(value) ? value : [value]; };
