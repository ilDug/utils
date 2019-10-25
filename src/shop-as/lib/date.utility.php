<?php

 /**
  * classe per la manipolazione delle date per le comunicazioni tra database, backend e frontend
  * conversione tra i formati di data
  *       ATTENZIONE: dal database devono arrivase solamente TIMESTAMP
  */
class DateParser
{

    function __construct()
    {
        # code...
    }



    /**
     * da TIMSTAMP MYSQL a TIMSTAMP Javascript in millisecondi
     */
    public function mysqlToTimestampJS($date){
        return strtotime($date) * 1000;
    }


    /**
     * da MYSQL a text
     */
    public function mysqlToText($date, $format = "d/m/Y"){
        //formati http://php.net/manual/en/function.date.php
        return date($format , strtotime($date));
    }


    /**
     * da timestamp Javascript (millisecondi) a TIMESTAMP MYSQL
     */
    public function timestampToMysql($date){
        return date("Y-m-d H:i:s", $date/1000);
    }


    /**
     * da timestamp Javascript (Secondi) a TIMESTAMP MYSQL
     */
    public function timestampSecToMysql($date){
        return date("Y-m-d H:i:s", $date);
    }


    /**
     * da string a TIMESTAMP MYSQL
     * i formati di stringa sono i seguenti
     *       date("jS F, Y", strtotime("11.12.10")); outputs 10th December, 2011
     *       date("jS F, Y", strtotime("11/12/10"));  outputs 12th November, 2010
     *       date("jS F, Y", strtotime("11-12-10"));  outputs 11th December, 2010
     */
    public function stringToMysql($date){
        return date("Y-m-d H:i:s", $date);
    }



}//chiude la classe
 ?>
