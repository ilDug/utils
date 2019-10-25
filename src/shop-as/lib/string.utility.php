<?php

 /**
  * classe per la manipolazione delle stringhe
  */
class StringTool
{

    function __construct()
    {
        # code...
    }


    /**
    * restituisce una stringa di numeri a caso della lunghezza voluta
	*/
	public function getRandomString($lunghezza){
			// lista di caratteri che comporranno la stringa random
			$caratteriPossibli = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			// inizializzo la stringa random
			$stringa = "";
			$i = 0;
			while ($i < $lunghezza) {
					// estrazione casuale di un un carattere dalla lista possibili caratteri
					$carattere = substr($caratteriPossibli, rand(0, strlen($caratteriPossibli)-1),1);
					$stringa .= $carattere;
					$i = 1 + $i;
			}
			return $stringa;
	}




	/**
    *   restituisce una paragrafo troncato al numero di lettere $len
	*   senza tagliare le parole e aggiungendo testo alla fiene $add default "...""
	*/
	public function truncateParagraph($str, $len, $add = "...") 	{
		if (strlen($str) <= $len) return $str;
		$a = wordwrap($str, $len, "|");
		$b=explode("|",$a);
		return $b[0].$add;
	}




	/**
    * sostituisce le vocali accentate con le corrispondenti compatibili con HTML
	*/
	public function replaceAccented($str){
		$search = array("á", "Á", "à", "À", "é", "É", "è", "È", "í", "Í", "ì", "Ì", "ó", "Ó", "ò", "Ò", "ú", "Ú", "ù", "Ù");
		$replace = array("&aacute;", "&Aacute;", "&agrave;", "&Agrave;", "&eacute;", "&Eacute;", "&egrave;", "&Egrave;", "&iacute;", "&Iacute;", "&igrave;", "&Igrave;", "&oacute;", "&Oacute;", "&ograve;", "&Ograve;", "&uacute;", "&Uacute;", "&ugrave;", "&Ugrave;");
		return str_replace($search, $replace , $str);
	}





}//chiude la classe
 ?>
