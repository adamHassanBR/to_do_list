<?php 
    require_once('./templates/header.php');

//******************************** Connexion à la BDD ********************************//
    require_once('./config/Bdd.php');
//************************************************************************************//
?>

    <link rel="stylesheet" href="./style/inscription.css"> 
    <title>Inscription</title>
</head>


<form class="formulaire_inscription" method="POST">
    
    <h4> Crée votre compte </h4>
    <br>
    <br>
    <center>
        <table>
            <tr>
                <td> <input style="width : 200px" class="form-control mr-sm-2" name="nom" type="text" required="required" placeholder="Votre nom"/></td>
                <td> <input style="width : 200px" class="form-control mr-sm-2" name="prenom" type="text" required="required" placeholder="Votre prénom"/></td>
            </tr>

        </table>
        <br>
        <input style="width : 400px" class="form-control mr-sm-2" name="mail" type="email" required="required" placeholder="Mail"/>
        <br>
        <input style="width : 400px" class="form-control mr-sm-2" name="mdp" type="password" required="required" placeholder="Mot De Passe"/>
    </center>
    <br>
    <input class="btn btn-outline-success" name="CreerCompte" type="submit" value="S'inscrire"/>
            
</form>

<?php
//**************************** Création du profil en BDD ****************************//


$CreerCompte = $_POST['CreerCompte'];
if($CreerCompte)
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];

        /////////// Dans le cas où le mail existe déja on redirige vers la page de connexion///////////
            $verifeMail = $conn->prepare("SELECT * FROM user WHERE mail=?");
            $verifeMail->execute([$mail]); 
            $maileExiste = $verifeMail->fetch();
                if ($maileExiste) 
                {
                    ?>
                    <div class="mailExiste">
                        Le mail saisie existe déjà <a href="connexion.php"> ce connecter </a>
                    </div>
                    <?php
                } 
                else
                {
                    $reqAdd = $conn->query("INSERT INTO user (nom, prenom, mail, mdp) VALUES ('$nom', '$prenom', '$mail', '$mdp')");
                    mail(
                        "$mail",
                        "NOUVEL UTILISATEUR",
                        "Bienvenue sur to do list"
                    );

                    //redirection sur la page de connexion
                    header("location:connexion.php");
                }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
    }

    
/*************************************************************************************/
?>

<?php require_once('./templates/footer.php') ?>