<?php
// require_once(__DIR__ . '/../app.config.php');


// class DBEngine
// {
// 	public $db;//oggetto MYSQLI


// 	public function __construct(){
//         $this->DbConnectAndSelect();
// 	}


// 	/**
// 	 * metodo di connessione al database
// 	 */
// 	protected function DbConnectAndSelect(){
// 		$this->db = new mysqli(HOST, USER, DBPASS, DB);
// 		if ($this->db->connect_error) {die("DAG_CONNECTION_ERR connection error  - Database non disponibile <br> " . $this->db->connect_error);}
// 	}



// 	/**
// 	* chiude la connessione al server
// 	*/
// 	public function DbCloseConn(){$this->db->close();}//metodo di chiusura della connessione al database


	
// 	/**
// 	 * cerca un elemento e lo ritorna come oggetto 
// 	 */
// 	public function findOne($sql){
// 		$data = $this->db->query($sql);
// 		if(!$data) return false;
// 		$row = $data->fetch_object();
// 		return $row;
// 	}



// 	/**	
// 	 * ricerca nel database una lista e restituisce un array di oggetti con proprietà
// 	 */
// 	public function findList($sql){
// 		$data = $this->db->query($sql);
// 		if(!$data) return false;
// 		while($row = $data->fetch_object()) { $result[] = $row; }
// 		return $result ? $result : false;
// 	}



// 	/**
// 	 * inserisce un nuovo elemento e ritorna il numero di _id nuovo 
// 	 */
// 	public function insertOneAndGetID($sql){
// 		$result = $this->db->query($sql);
// 		return $this->db->insert_id;
// 	}



// 	/**
// 	 * inserisce o modifica un record e ritorna il numero dei record modificti
// 	 */
// 	public function writeOne($sql){
// 		$result = $this->db->query($sql);
// 		return (int) $this->db->affected_rows;
// 	}




// 	/**
// 	 * elimina e restituisce il numero degli elementi eliminati
// 	 */
// 	public function deleteOne($sql){
// 		$result = $this->db->query($sql);
// 		return $this->db->affected_rows;
// 	}



// }//chiude la classe
?>