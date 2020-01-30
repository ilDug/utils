/**
 * basato su fuse.js
 */
export declare class Fuse {
    private options?;
    private list;
    private pattern;
    private results;
    private resultMap;
    private _keyMap;
    private fullSeacher;
    private tokenSearchers;
    constructor(list: any[], options?: IFuseOptions);
    /**
     * unica funzione pubblica che permette di eseguire la ricerca
     */
    search(pattern: string): any[];
    private defaultOptions;
    /**
     * Sets a new list for Fuse to match against.
     */
    private _set;
    private _prepareSearchers;
    private _startSearch;
    private _analyze;
    private _computeScore;
    private _sort;
    private _format;
    private deepValue;
    private isArray;
}
export declare class BitapSearcher {
    private pattern;
    private options;
    private patternLen;
    private matchmask;
    private patternAlphabet;
    constructor(pattern: any, options: any);
    private defaultOptions;
    /**
     * Initialize the alphabet for the Bitap algorithm.
     * @return {Object} Hash of character locations.
     * @private
     */
    private _calculatePatternAlphabet;
    /**
     * Compute and return the score for a match with `e` errors and `x` location.
     * @param {number} errors Number of errors in match.
     * @param {number} location Location of match.
     * @return {number} Overall score for match (0.0 = good, 1.0 = bad).
     * @private
     */
    private _bitapScore;
    /**
     * Compute and return the result of the search
     * @param {string} text The text to search in
     * @return {{isMatch: boolean, score: number}} Literal containing:
     *                          isMatch - Whether the text is a match or not
     *                          score - Overall score for the match
     * @public
     */
    private search;
    private _getMatchedIndices;
}
/**
 * interfacce delle opzioni extended
 */
export interface IFuseOptions extends ISearchOptions {
    id?: any;
    caseSensitive?: boolean;
    include?: string[];
    shouldSort?: boolean;
    searchFn?: any;
    sortFn?: (a: {
        score: number;
    }, b: {
        score: number;
    }) => number;
    getFn?: (obj: any, path: string, list?: any[]) => any;
    keys?: string[] | {
        name: string;
        weight: number;
    }[];
    verbose?: boolean;
}
/**
 * interfaccia delle opzioi base
 */
export interface ISearchOptions {
    tokenize?: boolean;
    tokenSeparator?: RegExp;
    matchAllTokens?: boolean;
    location?: number;
    distance?: number;
    threshold?: number;
    maxPatternLength?: number;
}
