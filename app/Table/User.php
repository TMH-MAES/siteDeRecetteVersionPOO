<?php
namespace App\Table;
class User {

    public function __construct() {
        
    }

    public function create($name, $email, $password) {
        return \App\App::getDb()->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)",[$name, $password], __CLASS__);
    
    }
    

    public function findByEmail($email) {
        return \App\App::getDb()->prepare("SELECT * FROM users WHERE email = ?", [$email],__CLASS__, true);
    }
}