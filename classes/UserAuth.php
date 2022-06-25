<?php

//including 'Dbh.php' file
include_once 'Dbh.php';
session_start();

//'UserAuth' class inherits from Dbh class
class UserAuth extends Dbh{

    //setting a $db property as static
    private static $db;

    //declaring a constructor function
    public function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if($this->validatePassword($password, $confirmPassword)){
            $sql = "INSERT INTO students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
            if($conn->query($sql)){
               echo "Ok";
            } else {
                echo "Opps". $conn->error;
            }
        }

        
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        $row=$result ->fetch_array(MYSQLI_ASSOC);
        if($result->num_rows > 0){
            $_SESSION['username'] = $row['full_names'];
            header("Location: dashboard.php");
        } else {
            header("Location: forms/login.php");
        }
    }

    public function getUser($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE full_names = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['id'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

    public function deleteUser($id){
        $conn = $this->db->connect();
        $sql = "DELETE * FROM students WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            header("refresh:0.5; url=action.php?all");
        } else {
            header("refresh:0.5; url=action.php?all=?message=Error");
        }
    }

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function updateUser($username, $password){
        $conn = $this->db->connect();
        if($this->checkEmailExist($this->email)){
            $sql = "UPDATE students SET password = '$password' WHERE full_names = '$username'";
            if($conn->query($sql) === TRUE){
                header("Location: dashboard.php?update=success");
            } else {
                header("Location: forms/resetpassword.php?error=1");
            }
        }
    }

    public function getUserByUsername($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE full_names = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function logout($email){
        session_start();
        session_destroy();
        header("Location: index.php");
        
       
    }

    public function validatePassword($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }

}