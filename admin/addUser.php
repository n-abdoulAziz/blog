<?php
include('../config/config.php');
include('../librairie/lib.php');

$vue='addUser.phtml';
$title = 'Tous les utilisateurs';
$activeMenu='user';


$username='';
$firstname='';
$lastname='';
$email='';
$password='';
$confirm='';
$role='';
$avatar='';
$bio='';

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
    
    try{
      /*generer une date */
      $created_date = date("Y-m-d");
      /** 1 : connexion au serveur de BDD - SGBDR */
      $dbh = connexion();
      $sql='INSERT INTO user (username, firstname,lastname,email,password,role,bio, created_date, last_login_date)
      VALUES
      (:username, :firstname, :lastname, :email, :password, :role, :bio, :created_date, :last_login_date)';
      $stmt = $dbh->prepare($sql);
  
      if(array_key_exists('username',$_POST))
      {
        $stmt->bindValue('username',$_POST['username']);
        $stmt->bindValue('firstname',$_POST['firstname']);
        $stmt->bindValue('lastname',$_POST['lastname']);
        $stmt->bindValue('email',$_POST['email']);
        $stmt->bindValue('password',$_POST['password']);
        $stmt->bindValue('role',$_POST['role']);
        $stmt->bindValue('bio',$_POST['bio']);
        $stmt->bindValue('created_date',$created_date);
        $stmt->bindValue('last_login_date',$created_date);
        //$stmt->bindValue('avatar',$_POST['avatar']);
      }
    
      $stmt->execute();
      echo 'Entrée ajoutée dans la table';
    }
          
    catch(PDOException $e){
      echo "Erreur : " . $e->getMessage();
    }
}


include('tpl/layout.phtml');

           


