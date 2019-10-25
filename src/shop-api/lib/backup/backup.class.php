<?php
require_once __DIR__ . '/app.config.php';
require_once __DIR__ . "/lib/pdo-connection.php";
require_once __DIR__ . "/lib/backup/backup-utility.php";
require_once __DIR__ . "/lib/backup/db-backup-info.php";
require_once __DIR__ . "/lib/backup/db-backup-print.php";

class BackUpDB{

    public $pdo;

    function __construct($pdo = null)
    {
        $this->pdo = $pdo ? $pdo : DagConnection::pdo();
       
       
        /** controlla che le directory di back up esisteano e siano montate */
        foreach ([BU_DIRECTORY_DB, BU_DIRECTORY_JSON] as $dir) {
            // if(!file_exists($dir)){ die('la directory dove salvare il backup non è stata trovata : ' . $dir); }
            if(!file_exists($dir)){ throw new Exception("la directory dove salvare il backup non è stata trovata : " . $dir, 500); die();}
        }
        
    }




    /**
     * salva copia del json nella cartella di backup
     * @param string $jsonName il nome del file (la directory è impostata automaticamente nelle confiigurazioni)
     * @param string $content il contenuto del file
     * @return int il numero di byte scritti or FALSE
     */
    public function saveJson($jsonName, $content, $zip = true)
    {
        return BackupUtility::saveFile(BU_DIRECTORY_JSON, $jsonName, $content, $zip);
    }




    

    /**
     * salva il file sql di una tabella
     * @return int filesize OR FALSE
     */
    public function tableToSQL($table, $zip = TRUE)
    {
        $content = \BACKUP\DATABASE\PrintSql::printBackupTable($table, $this->pdo);
        $name =  $table . '_' .(string)date("Y.m.d-His") . '.sql';
        return BackupUtility::saveFile(BU_DIRECTORY_DB, $name, $content, $zip);
    }






    /**
     * salva il file sql dell'intero database
     * @return int filesize OR FALSE
     */
    public function databaseToSQL($zip = TRUE)
    {
        $content = \BACKUP\DATABASE\PrintSql::printBackupDataBase($this->pdo);
        $name = 'DATABASE_' . (string)date("Y.m.d-His") . '.sql';
        return BackupUtility::saveFile(BU_DIRECTORY_DB, $name, $content, $zip);
    }

}//chiude la classe
 ?>