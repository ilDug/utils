
// export class Base36{
//     static chars = [ "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z" ];
// }


// export class Base36x2 extends Base36{
    
//     /**
//      * restituisce il codice successivo rispetto a qello passato come parametro
//      * @param prev il codice attuale
//      */
//     static next(prev: string): string{
//         let len = prev.length;

//         if (len > 2) throw new Error("la lunghezza del codice è superiore a 2 carateri");
//         if (prev == "zz") throw new Error("raggiunto il limite massimo");

//         prev = len == 2 ? prev : "0" + prev;
//         const prevList = prev.split('');

//         const i0: number = Base36.chars.indexOf(prevList[0])
//         const i1: number = Base36.chars.indexOf(prevList[1])

//         let next =[];
//         next[1] = prevList[1] == "z" ? "0" : Base36.chars[(i1 + 1)];
//         next[0] = prevList[1] == "z" ? Base36.chars[(i0 + 1)] : prevList[0];

//         return next[0] + next[1];
//     }
// }


