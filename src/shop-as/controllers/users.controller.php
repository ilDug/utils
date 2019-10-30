<?php
require_once __DIR__ . '/../lib/pdo-connection.php';

class UsersController
{
    public $pdo;
    
    public function __construct(){
        $this->pdo = DagConnection::pdo();
    }


    public function users_list(){
        $users = array();
        foreach ($this->pdo->query("SELECT * FROM ekusers.users_token_data;") as $user) {
            $user->authorizations = json_decode($user->authorizations);
            $users[] = $user;
        }
        return $users;
    }


}


?>