<?php
include('../config/config.php');
include('../librairie/lib.php');

$vue='addUser.phtml';
$title = 'Tous les utilisateurs';
$activeMenu='user';
CONST PASSWORD_MIN = 5;

$username='';
$firstname='';
$lastname='';
$email='';
$password='';
$confirm='';
$role='';
$avatar='';
$bio='';

$error ='';

try{
  //var_dump($_POST);
  if(array_key_exists('username',$_POST))
  {
      //Ajout d'un  nouveau utilisateur 
      $username=$_POST['username'];
      $firstname=$_POST['firstname'];
      $lastname=$_POST['lastname'];
      $password=$_POST['password']; 
      $confirm=$_POST['confirm'];
      $email=$_POST['email'];
      $role=$_POST['role'];
      $bio=$_POST['bio'];
      //$avatar=$_POST['avatar'];
      /*********firstname****** */
      if($firstname==''){
        $error ='please it s couldnt be void';
        var_dump($firstname);

      }
      elseif(!ctype_alpha($firstname)){
        $error='your firstname must be built by only letters';
      }
      /**************lastname************ */
      if($lastname==''){
        $error= 'error it musn t be void ';
      }
      elseif(!ctype_alpha($lastname)){
        $error='your lastname  by only letters';
      }
      /***********password*********/
      
      if($confirm!=$password){
        $error='please inser the same password';
      }
      if(strlen($password<PASSWORD_MIN)){
        $error='please enter 5 caracters min  in your password ';
      }

      
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      if($error == '')
      {
        /*generer une date */
        $created_date = new DateTime();
        /** 1 : connexion au serveur de BDD - SGBDR */
        $dbh = connexion();
        $sql='INSERT INTO user (username, firstname,lastname,email,password,role,bio, created_date, last_login_date)
        VALUES (:username, :firstname, :lastname, :email, :password, :role, :bio, :created_date, NULL)';
        $stmt = $dbh->prepare($sql);
    
        
        $stmt->bindValue('username',$username);
        $stmt->bindValue('firstname',$_POST['firstname']);
        $stmt->bindValue('lastname',$_POST['lastname']);
        $stmt->bindValue('email',$_POST['email']);
        $stmt->bindValue('password',$passwordHash);
        $stmt->bindValue('role',$_POST['role']);
        $stmt->bindValue('bio',$_POST['bio']);
        $stmt->bindValue('created_date',$created_date->format('Y-m-d H:i:s'));

        //$stmt->bindValue('avatar',$_POST['avatar']);
      
        $stmt->execute();
      
        header('Location:listUser.php');
        exit();
      }
  }
}          
catch(PDOException $e){
  echo "Erreur : " . $e->getMessage();
}

include('tpl/layout.phtml');

           


