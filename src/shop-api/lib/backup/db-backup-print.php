<?php


    /*************************************************** */
    /************ COMANDI PER STAMPARE IL TESTO DEL FILE */
    /*************************************************** */
namespace BACKUP\DATABASE;

class PrintSql
{
        /**
     * stampa il testo per la creazione di una tabella
     */
    private function printCreateTable($table, $pdo){
        $str = "";

        $st = $pdo->query('SHOW CREATE TABLE '. $table);
        while($row = $st->fetch(\PDO::FETCH_NUM)) {$str .=  $row[1];}

        $str .= ";";
        return $str;
    }






    /**
     * stampa il testo per assegnare la primary key
     */
    private function printAlterPrimaryKey($table, $pdo){
        $key = \BACKUP\DATABASE\Info::getPrimaryKeyName($table, $pdo);
        $str ='ALTER TABLE `' . $table . '` ADD PRIMARY KEY (`'. $key .'`);';
        return $str;
    }






    public function printInsertValues($table, $pdo){
        $columns = \BACKUP\DATABASE\Info::getFields($table, $pdo);
        $num_fields = count($columns);

        /** intestazione  */
        $str = "INSERT INTO $table (";
        for ($j = 0; $j < $num_fields; $j++){
            $str .= $columns[$j]["Field"];
            $str .= $j < ($num_fields - 1) ? ", " : "";
        }
        $str .= ") VALUES ";


        /** valori */
        $st = $pdo->query("select * from " . $table);
        $num_records = $st->rowCount();
        $k = 0;
        while($row = $st->fetch(\PDO::FETCH_NUM)) 
        {    
            $str.= "\n (";
            for($i = 0; $i<$num_fields; $i++)
            {
                $str .= isset($row[$i]) ?   \BACKUP\DATABASE\Info::discriminateString($columns[$i]["Type"], $row[$i])   :    "NULL";
                $str .=  $i < ($num_fields-1) ? ", " : "";
            }
            $str .= ") ";
            $str .= $k < ($num_records - 1 )  ? "," : ";";
            $k = $k + 1 ;
        }
        return $str;
    }







    /**
     * ritorna il testo del backup di una singola tabella
     * @return string
     */
    public function printBackupTable($table, $pdo){
        $str = "\n\n/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */ \n";
        $str .= "/* TABLE: " . $table . " */ \n";
        $str .= "/* back up created on: " . date("Y-M-d H:i:s") . " */";
        $str .= "\n/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */ \n \n \n";

        $str .= PrintSql::printCreateTable($table, $pdo);
        $str .= "\n\n/* -------------------------------------------------------- */ \n \n \n";


        $str .= PrintSql::printAlterPrimaryKey($table, $pdo);
        $str .= "\n\n/* -------------------------------------------------------- */ \n \n \n";


        $str .= PrintSql::printInsertValues($table, $pdo);
        $str .= " \n \n ";

        $str .= " \n /* ******************************************************** */";
        $str .= " \n /* ******************************************************** */";
        $str .= " \n /* ******************************************************** */ \n \n \n";
        return $str;
    } 
    

    
    

     /**
      * ottiene tutti i dati del database
      */
    public function printBackupDataBase($pdo){
        //ottiene la lista delle tabelle
        $tables = \BACKUP\DATABASE\Info::getTables($pdo);
        $str = "" ;

        //per ogni tabella salva i dati in una variabile testuale
        foreach ($tables as $table) {
            $str .= PrintSql::printBackupTable($table, $pdo);
        }
        return $str;
    }
    
}// chiude la classe





