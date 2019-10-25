<?php
     /*************************************************** */
     /************ COMANDI PER INFO SUL DATABASE ******** */
     /*************************************************** */

namespace BACKUP\DATABASE;

class Info
{
        
    /**
     * ottine la lista delle tabelle 
     */
    public function getTables($pdo){
        $tables = array();
        $st = $pdo->query("SHOW TABLES");
        while($row = $st->fetch(\PDO::FETCH_NUM )) {$tables[] = $row[0];}
        return $tables;
    }





    /**
     * ottien i campi di una tabella
     */
    public function getFields($table, $pdo){
        $columns = array();
        $sql  = 'SHOW COLUMNS FROM '. $table;
        $st = $pdo->query($sql);
        while($row = $st->fetch(\PDO::FETCH_ASSOC)) {$columns[] = $row;}
        return $columns;
        /* Field | Type | Null | Key | Default | Extra */
    }







    /**
     * ottiene il nome del campo PRIMARY della tabella
     */
    public function getPrimaryKeyName($table, $pdo){
        $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
        $st = $pdo->query($sql);
        $res = $st->fetch();
        return $res->Column_name;
    }








    public function discriminateString($type, $value){
        $t = explode('(',$type)[0];
        switch ($t) {
            case 'varchar':         return  "'" . $value . "'" ; break;
            case 'int':             return  $value ; break;
            case 'float':           return  $value ; break;
            case 'text':            return  "'" . $value . "'" ; break;
            case 'timestamp':       return  "'" . $value . "'" ; break;
            default:                return  "'" . $value . "'" ; break;
        }
    }
    
} //chiude la classe


