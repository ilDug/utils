<?php

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

class Base36
{
    static $chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

    static public function next($prev)
    {
        $prev = (string) $prev;
        if (!$prev) return "0";

        $i = array_search($prev, self::$chars);
        $next = $prev == "z" ? "0" : self::$chars[((int) $i + 1)];
        return $next;
    }
}

class Base36x2 extends Base36
{
    /**
     * restituisce il codice successivo rispetto a qello passato come parametro
     * @param prev il codice attuale
     */
    static public function next($prev)
    {
        $prev = (string) $prev;
        if (!$prev) return "00";

        $list = str_split($prev);
        $list = array_reverse($list);

        // $i = array_search($list[0], self::$chars);
        // $ii = array_search($list[1], self::$chars);

        $next = array();
        $next[0] = Base36::next($list[0]);
        $next[1] = Base36::next($list[1]);

        $next[1] = $list[0] == "z" ? "0" :

            $next[0] = $prev[1] == "z" ? "0" : self::$chars[((int) $i1 + 1)];
        $next[0] = $prev[1] == "z" ? self::$chars[((int) $i0 + 1)] : $prev[0];

        return $next[0] . $next[1];
    }
}
