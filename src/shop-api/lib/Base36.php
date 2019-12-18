<?php

class Base36
{
    static $chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

    static public function next($prev)
    {
        $prev = (string) $prev;
        if (!isset($prev)) return "0";

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

        /** crea array delle cifre */
        $list = str_split($prev);

        /** invete l'ordine in modo che nell'indice 0 ho la prima cifra di destra */
        $list = array_reverse($list);


        $next = array();
        $next[0] = Base36::next($list[0]);
        /** aument solo se il tutti i prev precedenti sono "z" */
        $next[1] = $list[0] === "z" ? Base36::next($list[1]) : $list[1];

        $next = array_reverse($next);

        return join("", $next);
    }
}


class Base36x3 extends Base36
{
    /**
     * restituisce il codice successivo rispetto a qello passato come parametro
     * @param prev il codice attuale
     */
    static public function next($prev)
    {
        $prev = (string) $prev;
        if (!$prev) return "000";

        /** crea array delle cifre */
        $list = str_split($prev);

        /** invete l'ordine in modo che nell'indice 0 ho la prima cifra di destra */
        $list = array_reverse($list);


        $next = array();
        $next[0] = Base36::next($list[0]);
        /** aument solo se il tutti i prev precedenti sono "z" */
        $next[1] = $list[0] === "z" ? Base36::next($list[1]) : $list[1];
        $next[2] = ($list[0] === "z" && $list[1] === "z") ? Base36::next($list[2]) : $list[2];

        $next = array_reverse($next);

        return join("", $next);
    }
}
