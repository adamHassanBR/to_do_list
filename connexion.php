<?php 
    require_once('./templates/header.php');
//******************************** Connexion à la BDD ********************************//
    require_once('./config/Bdd.php');
//************************************************************************************//
?>

    <link rel="stylesheet" href="./style/connexions.css"> 
    <title>Connexion</title>
</head>

<form  class="formulaire_connexion" name="Connexion" method="POST">
    <center>
        <br>
        <h4> Se Connecter </h4>
        <br>
        <h5> To Do List </h5>
        <p>
            -   Organiser ces tâches
            <br>
            -   Faire un point sur ces tâches
        </p>
        <br>
        <input style="width : 200px" class="form-control mr-sm-2" name="mail" type="text" required="required" placeholder="Mail"/>
        <br>
        <input style="width : 200px" class="form-control mr-sm-2" name="mdp" type="password" required="required" placeholder="Mot de Passe"/>
        <br>
        <input class="btn btn-success" name="ConnexionB" type="submit" value="Se Connecter"/>
        <br><br>
        
    </center>
</form>
<?php require_once('./templates/footer.php') ?>
<?php

 //**************************** Connexion utilisateur établie ****************************//
 session_start();
 
 if(isset($_POST['ConnexionB'])) 
 {
     $mailconnect = $_POST['mail'];
     $mdpconnect = $_POST['mdp'];
     if(!empty($mailconnect) AND !empty($mdpconnect)) 
     {
         $requser = $conn->prepare("SELECT * FROM user WHERE mail = ? AND mdp = ?");
         $requser->execute(array($mailconnect, $mdpconnect));
         $userexist = $requser->rowCount();
         if($userexist == 1) 
         {
             $userinfo = $requser->fetch();
             $_SESSION['id_user'] = $userinfo['id_user'];

             header("Location: menue.php?id_user=".$_SESSION['id_user']);
             header("Location: list.php");

            //redirection sur la page du menue
            header("location:menue.php");
         } 
         else 
         {
             ?>
             <center>
                 <p class="msgErreure">
                     Mauvais mail ou mauvais mot de passe !
                 </p>
             </center>
             <?php
         }
     } 
 }

 /*************************************************************************************/
?>