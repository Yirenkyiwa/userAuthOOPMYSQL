<?php

//creating a class
class Dbh{

    //declaring properties of the class 'Dbh'
    public $hostname="127.0.0.1";
    public $username="root";
    public $password="";
    public $dbname="zuriphp";

    //creating a method for database connection
    protected function connect(){
        $conn=mysqli_connect($this->hostname,$this->username,$this->password,$this->dbname);

        if(!$conn){
            echo "Error: " . mysqli_connect_error();
        }else{
            return $conn;
        }
    }

}
?>


