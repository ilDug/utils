<?php

class BackupUtility
{
    /**
     * crea il file zip
     * @return boolean
     */
    public function zipBU($directory, $name , $text){
        $zip = new \ZipArchive;
        if ($zip->open($directory . $name . '.zip', ZipArchive::CREATE) === TRUE)
        {
            $res = $zip->addFromString($name, $text);
            $zip->close();
            if(!$res) { throw new Exception("500 errore di scrittura del file Zip");}
            return $res;
        }else{
            throw new Exception("500 errore di creazione del file Zip" );
            return false;
        }
    }




    /**
     * salva il file sul server nella directory impostata nelle configurazioni
     * @param string $name il nome del file
     * @param string $content il contenuto del file
     * @param boolean $zip , se il file deve essere compresso
     */
    public function saveFile($directory, $name, $content, $zip){
        if($zip){

            /** salva il file Zip  e ritorna la dimensione del file creato*/
            if(BackupUtility::zipBU($directory, $name, $content))
            {
                return filesize($directory . $name .'.zip');
            }

        }else{

            $written =  file_put_contents($directory . $name, $content);
            if(!$written) { throw new Exception("errore di scrittura del file durnate il backup"); }
            return $written;
        }
    }




}//chiude classe
