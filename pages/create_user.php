<?php
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if($password === $confirm_password) {
            $password = password_hash($password, PASSWORD_ARGON2ID);
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mywebsite";

            try {
                $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connexion failed: " . $e->getMessage();
            }
            //verify if user already exist
            $req = $bdd->prepare("SELECT * FROM users WHERE email = :email Limit 1");
            $req->bindValue(':email', $email);
            $req->execute();

            $userExist = $req->fetch(PDO::FETCH_ASSOC);
            if (!$userExist) {
                $req = $bdd->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
                $req->bindValue(':name', $name);
                $req->bindValue(':email', $email);
                $req->bindValue(':password', $password);
                $req->execute();
                echo "User created successfully";
            } else {
                echo "User already exist";
            }
        }

        } else {
            echo "Passwords do not match";
        }
    } else {
        echo "All fields are required";
    }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mywebsite";

try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connexion failed: " . $e->getMessage();
}
