<?php 
    require_once('./templates/header.php');

//******************************** Connexion à la BDD ********************************//
    require_once('./config/Bdd.php');
//************************************************************************************//
session_start();
// récuperation de id_list dans le lien
$lId_list = $_GET['id_list'];
// récuperation des data lier à la list
$sql = "SELECT * FROM list WHERE id_list = :iDlist";
$sth = $conn->prepare($sql);
$sth->execute(array(
    ':iDlist' => $lId_list
));

$datalist = $sth->fetch();
$namList = $datalist['nom_list']; 
?>

    <link rel="stylesheet" href="./style/list.css"> 
    <title>list</title>
</head>
<body>

<div class="grosTitre">
    <div class="row">
        <div class="col">
            <h2><a href="menue.php" style="color:#fff;">Menu</a></h2>
        </div>
        <div class="col">
            <h1 ><?= $namList ?></h1>
        </div>
    </div>
</div>
<?php
 ////////// Requette permetant de savoir combien il y a d'element dans la table avec le bon id//////////
 $res = $conn->query("SELECT count(*) AS nb FROM task WHERE id_list='$lId_list' AND statut_task='1'");
 $data = $res->fetch();
 $nbLigne = $data['nb'];
 ///////////////////////////////////////////////////////////////////////////////////////
 ////////// Dans le cas où toute les tâches ont été faite on place la liste en terminer//////////
 if($nbLigne==0)
 {
    $reqModiflistStatut = $conn->query("UPDATE list SET statut_list ='1' WHERE id_list='$lId_list'");
 }
 else
 {
    $reqModiflistStatut = $conn->query("UPDATE list SET statut_list ='0' WHERE id_list='$lId_list'");
 }
 ///////////////////////////////////////////////////////////////////////////////////////
?>
<div class="row" style="padding:20px;">
    <div class="col">
        <form method="POST" class="formRegisterTask">
            <div class="mesTaches">
            
                    <br>
                    <?php
                    $priorite=0;
                    $aficheTask = $conn->query("SELECT * FROM task WHERE id_list ='$lId_list' AND statut_task='1' ORDER BY deadline_task ASC");
                    foreach($aficheTask as $aficheTask)
                    {
                        $priorite++;
                        $id_task = $aficheTask['id_task'];
                        $statut_task = $aficheTask['statut_task'];
                        $la_task = $aficheTask['la_task'];
                        $deadline_task = $aficheTask['deadline_task'];
                        ?>
                        <div class="row">
                            <div class="col">
                                <div class="compteur">
                                    <?= $priorite;?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="la_task">
                                    <div class="row">
                                        <div class="col">
                                                <?= $la_task;?>
                                        </div>
                                        <div class="col deadline_task">
                                            <?= $deadline_task;?>
                                        </div>                     
                                    </div>
                                </div>                       
                            </div>
                            <div class="col-1">
                            <input type="checkbox" name="checkboxName[]" value="<?=$id_task?>">
                            </div>
                        </div>
                        <br>
                        <?php
                    }

                    $aficheTask = $conn->query("SELECT * FROM task WHERE id_list ='$lId_list' AND statut_task='0' ORDER BY deadline_task ASC");
                    foreach($aficheTask as $aficheTask)
                    {
                        $priorite++;
                        $id_task = $aficheTask['id_task'];
                        $statut_task = $aficheTask['statut_task'];
                        $la_task = $aficheTask['la_task'];
                        $deadline_task = $aficheTask['deadline_task'];
                        ?>
                        <div class="row">
                            <div class="col">
                                <div class="compteur">
                                    <?= $priorite;?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="la_task">
                                    <div class="row">
                                        <div class="col">
                                                <?= $la_task;?>
                                        </div>
                                        <div class="col deadline_task">
                                            <?= $deadline_task;?>
                                        </div>                     
                                    </div>
                                </div>                       
                            </div>
                            <div class="col-1">
                                <input type="checkbox" name="checkboxName[]" value="<?=$id_task?>" checked>
                            </div>
                        </div>
                        <br>
                        <?php
                    }
                ?>
            
            </div>
            <br>
            <input type="submit" value="Enregistrer" name="buttonRegisterTask" class="btn btn-success" style="background-color:#F64C72; border-color:#F64C72;">
        </form>
    </div>

    <div class="col">
    <form method="POST" class="formRegisterNote">
            <?php
                $aficheNote = $conn->query("SELECT * FROM note_list WHERE id_list='$lId_list'");
                foreach($aficheNote as $aficheNote)
                {
                    $laNote = $aficheNote['list_note'];                    
                }
            ?>
            <div class="formRegisterNotee">
            <textarea name="Note" class="form-control noteImportante" id="exampleFormControlTextarea1" rows="30"><?=$laNote?></textarea>
            </div>
            <br>
            <input type="submit" value="Enregistrer" name="buttonRegisterNote" class="btn btn-success" style="background-color:#F64C72; border-color:#F64C72;">
        </form>
    </div>
</div>
<br>
<form method="POST" class="formAddTaches">
    <input type="text" name="tache" placeholder="Faire...">
    <input type="date" name="dateFin" value="<?php echo date('Y-m-d'); ?>">
    <br><br>
    <input type="submit" value="Ajouter" name="buttonAddTask" class="btn btn-success btn-sm" style="background-color:#F64C72; border-color:#F64C72;">
</form>

<?php require_once('./templates/footer.php') ?>

<?php


//**************************** enregistrer note ****************************//
$Note = $_POST['Note'];
$buttonRegisterNote = $_POST['buttonRegisterNote'];
if($buttonRegisterNote)
{
    ////Ajoute en bdd/////

    ////////// Requette permetant de savoir combien il y a d'element dans la table avec le bon id//////////
    $res = $conn->query("SELECT count(*) AS nb FROM note_list WHERE id_list='$lId_list'");
    $data = $res->fetch();
    $nbLigne = $data['nb'];
    ///////////////////////////////////////////////////////////////////////////////////////

    ////Savoir si l'utilisateur à deja une note ou non ////
    if($nbLigne==0) // si il n'as pas de note on en crée une 
    {
        $reqAddNote = $conn->query("INSERT INTO note_list (id_list, list_note) VALUES ('$lId_list', '$Note')");
    }
    else // sinon on modifi sa note existante 
    {
        $reqModifNote = $conn->query("UPDATE note_list SET list_note ='$Note' WHERE id_list='$lId_list'");
                                                
    }

    //refreche pour actualiser l'afichage des modif de la bdd
    ?>
        <meta http-equiv="refresh" content="0.01" />
    <?php
}
/****************************************************************************/

//**************************** update des task ****************************//
$buttonRegisterTask = $_POST['buttonRegisterTask'];
if($buttonRegisterTask)
{
    ////modification en bdd/////

    // On met toute les task comme terminer
    $reqModifNote = $conn->query("UPDATE task SET statut_task ='1' WHERE id_list='$lId_list'");
    // si les cheskbox sont cocher
    $checkboxName = $_POST['checkboxName'];
    foreach($checkboxName as $value)
    {
        $reqModifNote = $conn->query("UPDATE task SET statut_task ='0' WHERE id_task='$value'");
    }
    //refreche pour actualiser l'afichage des modif de la bdd
    ?>
        <meta http-equiv="refresh" content="0.01" />
    <?php
}
/**************************************************************************/


//**************************** Ajouter une task ****************************//
$buttonAddTask = $_POST['buttonAddTask'];
$tache = $_POST['tache'];
$dateFin = $_POST['dateFin'];
if($buttonAddTask)
{
    ////modification en bdd/////
    $reqAddNote = $conn->query("INSERT INTO task (id_list, statut_task, la_task, deadline_task) VALUES ('$lId_list', '1', '$tache', '$dateFin')");
    //refreche pour actualiser l'afichage des modif de la bdd
    ?>
        <meta http-equiv="refresh" content="0.01" />
    <?php
}
/****************************************************************************/

?>
