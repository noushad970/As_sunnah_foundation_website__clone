<?php
session_start();
require 'db.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();

        if(!$user['is_verified']){
            echo "❌ Please verify your email first.";
            exit();
        }

        if(password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['id'];
            echo "✅ Login success";
        }else{
            echo "❌ Wrong password";
        }
    }else{
        echo "❌ User not found";
    }
}
?>
