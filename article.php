<?php
session_start(); //creer une session pour faire transiter des infos

require_once 'config/connexion.inc.php';
require_once 'config/bdd.inc.php'; // on appele la base de données
/* * **SMARTY*** */
require_once ('libs/Smarty.class.php');
$smarty = new Smarty(); //creation d'objet smarty
$smarty->setTemplateDir('template/');
/* * **Smarty*** */
if (isset($_POST['envoyer'])) {//verifie si le bouton envoyé est posté je traite les données
    $titre = addcslashes($_POST['titre'], "'%_"); //sécurisation des variables rajoute slash avant '%_
    $texte = addcslashes($_POST['texte'], "'%_"); //créer la zone de texte (corps de l'article)
    //print_r($_POST);
    $publie = (!empty($_POST['publie']) ? $_POST['publie'] : 0); // definition de la valeur " "publié"
    $date = date("Y-m-d"); //reprise  de la date du système
    //echo $titre . '&' . $texte . '&' . $publie . '&' . $date;
    $req_insertion = "INSERT INTO articles (titre, texte, date, publie) VALUES('$titre', '$texte', '$date', $publie)";
    //echo $req_insertion;
    $erreur_image = $_FILES['image']['error']; //créer le fichier "parcourir" pour ajouter la photo

    if ($erreur_image != 0) {//si il y a une erreur
        $_SESSION ['MAIL_ERROR'] = "CRITICAL ERROR => YOUR COMPUTEUR WILL BE SHUTDOWN AND RESTART!!!!!!"; //declarer une session d'erreur
        //echo 'CRITICAL ERROR => YOUR COMPUTEUR WILL BE SHUTDOWN AND RESTART!!!!!!';//afficher le texte
        header("location:article.php"); //redirection vers la page d'article
    } else {//sinon
        //echo 'direname';
        mysql_query($req_insertion); //inserer 
        $id_article = mysql_insert_id(); //   la photo dans l'article
        //echo$id_article;
        move_uploaded_file($_FILES['image']['tmp_name'], dirname(__FILE__) . "/img/$id_article.jpg"); //charger la photo dans le dossier img quelque part sur le serveur
        $_SESSION ['MAIL_OK'] = "CRITICAL ERROR => You are the best devellopeur!"; //declarer une session de validité
        //echo 'You are the best devellopeur!';//est affiché le texte suivant
        header("location:index.php"); //redirection vers la page d'article
    }
} else {
    ?>
    <?php
    include_once 'include/header.inc.php'; //appeler le header HTML
    /* ------------------------ HTML --------------------------- */
    if (isset($_SESSION['MAIL_ERROR'])) { // si erreur, appele la session du message d'erreur
        ?>
        <div class="alert alert-error" id="notif"
             <?php echo $_SESSION['MAIL_ERROR'];
             ?></div><?php
        unset($_SESSION['MAIL_ERROR']); //termine le script
    } {

        $smarty->assign('connect', $_SESSION['connect']);

//** un-comment the following line to show the debug console
        $smarty->debugging = true;

        $smarty->display('article.tpl');

        unset($SESSION['connect']); //destruction de la variable de session
    }
}
    ?>
    <?php
    include_once 'include/menu.inc.php';
    include_once 'include/footer.inc.php';
    ?>