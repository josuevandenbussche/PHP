<?php

session_start();
require_once 'config/connexion.inc.php';
/* * **SMARTY*** */
require_once ('libs/Smarty.class.php');
$smarty = new Smarty(); //creation d'objet smarty
$smarty->setTemplateDir('template/');
/* * **Smarty*** */
include_once 'include/header.inc.php'; //appele le header dans le fichier HTML
require_once 'config/bdd.inc.php'; // on appele la base de données

if (isset($_POST['connect'])) {
    $email = addcslashes($_POST['Email'], "'%_");
    $password = addcslashes($_POST['password'], "'%_");
    print_r($_POST);
    $search = "SELECT * FROM connect WHERE =$email AND =$password";
    $a = mysql_query($search);
    $verification = mysql_fetch_array($a);



    if ($verification) {
        $sid = md5($email . time());
        $id = $verification['id'];
//echo $sid;
        $maj = 'UPDATE connect SET sid ="$sid" WHERE id="$id"';
        $request = mysql_query($maj);

        setcookie('sid', "$sid", time() + (30 * 60)); //en seconde
        $_SESSION ['confirm'] = "Connexion établie"; //declarer une session de validité
        header("location:index.php"); //redirection vers la page d'article
    }
} else {

    if (isset($_SESSION['connect'])) {

        $smarty->assign('connect', $_SESSION['connect']);

//** un-comment the following line to show the debug console
        $smarty->debugging = true;

        $smarty->display('connection.tpl');

        unset($_SESSION['connect']); //destruction de la variable de session



        include_once 'include/menu.inc.php';
        include_once 'include/footer.inc.php';
    }
}
?>