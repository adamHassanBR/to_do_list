<?php 
    require_once('./templates/header.php');

//******************************** Connexion à la BDD ********************************//
    require_once('./config/Bdd.php');
//************************************************************************************//
session_start();
$LID = $_SESSION['id_user'];



    //**************************** Ajout liste ****************************//
    $listColor = $_POST['listColor'];
    $listName = $_POST['listName'];
    $buttonAddList = $_POST['buttonAddList'];
    if($buttonAddList)
    {
        /////Ajoute en bdd/////
        $reqAddList = $conn->query("INSERT INTO list (id_user, nom_list, statut_list, color_list) VALUES ('$LID', '$listName', '0', '$listColor')");
        //refreche pour actualiser l'afichage de smodif de la bdd
        ?>
            <meta http-equiv="refresh" content="0.01" />
        <?php
    }
    /***********************************************************************/

    //**************************** supprimer liste ****************************//
    
    $id_list = $_POST['id_list'];
    $suprimerList = $_POST['suprimerList'];
    if($suprimerList)
    {
        $reqSupprimer = $conn->query("DELETE FROM list WHERE id_list='$id_list'"); //Supression des informations dans la BDD par raport à l'id
        //refreche pour actualiser l'afichage de smodif de la bdd
        ?>
            <meta http-equiv="refresh" content="0.01" />
        <?php
    }
    /***************************************************************************/



    //**************************** enregistrer note ****************************//
    $Note = $_POST['Note'];
    $buttonRegisterNote = $_POST['buttonRegisterNote'];
    if($buttonRegisterNote)
    {
        ////Ajoute en bdd/////

        ////////// Requette permetant de savoir combien il y a d'element dans la table avec le bon id//////////
        $res = $conn->query("SELECT count(*) AS nb FROM user_note WHERE id_user='$LID'");
        $data = $res->fetch();
        $nbLigne = $data['nb'];
        ///////////////////////////////////////////////////////////////////////////////////////

        ////Savoir si l'utilisateur à deja une note ou non ////
        if($nbLigne==0) // si il n'as pas de note on en crée une 
        {
            $reqAddNote = $conn->query("INSERT INTO user_note (id_user, note_user) VALUES ('$LID', '$Note')");
        }
        else // sinon on modifi sa note existante 
        {
            $reqModifNote = $conn->query("UPDATE user_note SET note_user ='$Note' WHERE id_user='$LID'");
                                                    
        }

        //refreche pour actualiser l'afichage de smodif de la bdd
        ?>
            <meta http-equiv="refresh" content="0.01" />
        <?php
        
    }
    /****************************************************************************/


?>

    <link rel="stylesheet" href="./style/menue.css"> 
    <title>To do list</title>
</head>
<body>
<h1 class="grosTitre">TO DO LIST</h1>

<div class="row">
    <div class="col">
        <h2 class="titreTdl "> Mes to do list</h2>
        <div class="scroling mesToDoList">
            <?php
                $aficheTDL = $conn->query("SELECT * FROM list WHERE statut_list ='0' and id_user='$LID'");
                foreach($aficheTDL as $aficheTDL)
                {
                    $color_list = $aficheTDL['color_list'];
                    $nom_list = $aficheTDL['nom_list'];
                    $id_list = $aficheTDL['id_list'];
                    ?>
                    <a href="list.php?id_list=<?=$id_list?>" style="text-decoration: none; color: black;">
                    <div class="row tableTdl">
                            <div class="col-1 cercleColor" style="background-color:<?=$color_list?>;">
                            </div>

                            <div class="col" style="font-size:10px; text-align:left">
                                <?=$nom_list?>
                            </div>
                        </div>
                    </a>
                    <br>
                    <?php
                }
            ?>
        </div>
        <br>
        <form method="POST" class="formAddListe">
            <?php
                $randColor='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            ?>
            <input type="color" name="listColor" value="<?=$randColor?>">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="listName" placeholder="liste de course" style="font-size:10px;" required="required">
            &nbsp;&nbsp;&nbsp;
            <input type="submit" value="Ajouter" name="buttonAddList" class="btn btn-success btn-sm" style="background-color:#F64C72; border-color:#F64C72; font-size:10px;">
        </form>
    </div>
   


    <div class="col-7 groupeNote">
        <h2 class="titreNote"> Mes Notes importantes</h2>
        <form method="POST" class="formRegisterNote">
            <?php
                $aficheNote = $conn->query("SELECT * FROM user_note WHERE id_user='$LID'");
                foreach($aficheNote as $aficheNote)
                {
                    $laNote = $aficheNote['note_user'];                    
                }
            ?>
            <div class="formRegisterNotee">
            <textarea name="Note" class="form-control noteImportante" id="exampleFormControlTextarea1" rows="30"><?=$laNote?></textarea>
            </div>
            <br>
            <input type="submit" value="Enregistrer" name="buttonRegisterNote" class="btn btn-success" style="background-color:#F64C72; border-color:#F64C72;">
        </form>
    </div>
    <div class="col">
        <h2 class="titreTdl"> To do list terminer</h2>
        <div class="scroling toDoListTerminer">
        <?php
                $aficheTDL = $conn->query("SELECT * FROM list WHERE statut_list ='1' and id_user='$LID'");
                foreach($aficheTDL as $aficheTDL)
                {
                    $color_list = $aficheTDL['color_list'];
                    $nom_list = $aficheTDL['nom_list'];
                    $id_list = $aficheTDL['id_list'];
                    ?>
                    <form method="POST">
                        <div class="row tableTdl1">
                            <div class="col-1 cercleColor" style="background-color:<?=$color_list?>;">
                            </div>
                            <div class="col" style="font-size:10px; text-align:left">
                                <input name="id_list" type="text" value="<?=$id_list?>" style="display: none">
                                <?=$nom_list?>
                            </div>
                            <div class="col" style="text-align:right">
                               <button class="btn btn-info btn-sm" style="font-size:10px;"><a href="list.php?id_list=<?=$id_list?>" style="text-decoration: none; color: black;">Modifier</a></button>
                            </div>
                            <div class="col" style="text-align:right">
                                <input type="submit" class="btn btn-danger btn-sm" value="Supprimer" style="font-size:10px;" name="suprimerList">
                            </div>
                        </div>
                    </form>
                  
                    <br>
                    <?php
                }
            ?>
        </div>
        <br>
        <br>
    </div>
</div>




<?php require_once('./templates/footer.php') ?>