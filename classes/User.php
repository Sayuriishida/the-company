<?php

require_once "Database.php";

class User extends Database
{
    public function store($request)
    {
        $first_name = ucwords(strtolower($request['first_name']));
        // $_POST['first_name]
        $last_name = ucwords(strtolower($request['last_name']));
        $username = $request['username'];
        $password = password_hash($request['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name, last_name, username, password) 
        VALUES ('$first_name', '$last_name', '$username', '$password')";

        if ($this->conn->query($sql)) {
            header('location: ../views'); //go to views/index.php
            exit;
        } else {
            die('Error creating new user: ' . $this->conn->error);
        }
    }

    public function login($request)
    {
        $username = $request['username'];
        $password = $request['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";

        $result = $this->conn->query($sql);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Create session variables for future use
                session_start();
                $_SESSION['id']         = $user['id'];
                $_SESSION['username']   = $user['username'];
                $_SESSION['full_name']  = $user['first_name'] . " " . $user['last_name'];

                header('location: ../views/dashboard.php');
                exit;
            } else {
                die('Incorrect Password');
            }
        } else {
            ('Username not found.');
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header('location: ../views');
        exit;
    }

    public function getAllUsers()
    {
        $sql = "SELECT id, first_name, last_name, username, photo FROM users";

        if ($result = $this->conn->query($sql)) {
            return $result;
        } else {
            die('Error retrieving all users: ' . $this->conn->error);
        }
    }

    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE id = $id";

        if ($result = $this->conn->query($sql)) {
            return $result->fetch_assoc();
        } else {
            die('Error retrieving the user: ' . $this->conn->error);
        }
    }

    public function update($request, $files){
        session_start();
        $id = $_SESSION['id'];
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $username = $request['username'];
        $photo = $files['photo']['name'];
        $tmp_photo = $files['photo']['tmp_name'];

        $first_name = ucwords(strtolower($first_name));
        $last_name = ucwords(strtolower($last_name));

        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username', photo = '$photo' WHERE id = $id";
    
        if($this->conn->query($sql)){
            $_SESSION['full_name'] = $first_name . " " . $last_name;
            $_SESSION['username'] = $username;

            # if there is a photo uploaded, save it to your database and save the file to your uploads(assets/images) folder
            if($photo){
                $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
                $desination = "../assets/images/$photo";

                // Save the image to the uploads folder
                if($this->conn->query($sql)){
                    // save the file to the uploads(assets/images) folder
                    if(move_uploaded_file($tmp_photo, $desination)){
                        header('location: ../views/dashboard.php');
                        exit;
                    } else {
                        die('Error moving photo to uploads folder.');
                    }
                } else {
                    die('Error updating the photo: ' . $this->conn->error);
                }
            }
            header('location: ../views/dashboard.php');
            exit;
        }else{
            die('Error updating user: ' . $this->conn->error);
        }
    }

    public function delete(){
        session_start();
        $id = $_SESSION['id'];

        $sql = "DELETE FROM users WHERE id = $id";

        if($this->conn->query($sql)){
            $this->logout();
            exit;
        } else {
            die('Error deleting user: ' . $this->conn->error);
        }
    }


}