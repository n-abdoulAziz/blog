
<?php
include('../config/config.php');
include('../librairie/lib.php');

echo  
'welcom';
try
{
    /** On envoi une exception si l'id n'est pas passée dans la chaîne de requête
     * Le reste des lignes du bloc try ne sera pas executé
     * On va directement au bloc catch
     */
    if (!array_key_exists('user', $_POST) || !is_numeric($_POST['user'])) {
        throw new Exception('Tu fais quoi ici ?');
    }
    
    $customerNumber = $_['id'];

    /** 1 : connexion au serveur de BDD - SGBDR */
    $dbh = connexion();

    /**2 : Prépare ma requête SQL */
    $sth = $dbh->prepare('SELECT * FROM '.DB_PREFIXE.'customers WHERE customerNumber = ?');

    /** 3 : executer la requête et bindage en une ligne
     * Attention : ici je fais confiance à PDO pour binder correctement la valeur.
     * J'utilise donc un ? dans la préparation de la requête et je passe un tableau indéxé à execute.
     * Dans un projet sérieux on préfèrera utiliser bindValue ou bindParam comme je vous l'ai montré. 
     * Mais dans la réalité vous pourrez aussi être confronté à des requête avec des ?. C'est pour 
     * cette raison que je vous présente cela dans cette correction et pour toutes les requêtes ! 
    */
    $sth->execute(array($customerNumber));

    /** 4 : recupérer les résultats 
     * On utilise FETCH car un seul résultat attendu
    */
    $customer = $sth->fetch(PDO::FETCH_ASSOC);


    /** On va maintenant récupérer toutes les commandes du 
     * client en faisant une nouvelle requête 
     * On est déjà connecté donc inutile de se reconnecter au serveur
     * On commence à l'étape 2 
    */
    /**2 : Prépare ma requête SQL */
    $sth = $dbh->prepare('SELECT * FROM '.DB_PREFIXE.'orders WHERE customerNumber = ?');
    /** 3 : executer la requête */
    $sth->execute(array($customerNumber));
    /** 4 : recupérer les résultats */
    $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

}
catch(PDOException $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
}
catch(Exception $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée
    $messageErreur =  'Erreur dans la page :'.$e->getMessage();
}



include('tpl/' . LAYOUT . '.phtml');


//include('addUser.php');
//$vue2='listUser.phtml'
include('tpl/listUser.phtml');



