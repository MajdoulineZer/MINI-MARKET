<?php
require_once("../inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- VERIFICATION ADMIN ---//
if(!internauteEstConnecteEtEstAdmin())
{
    header("location:../connexion.php");
    exit();
}
 
//--- SUPPRESSION MEMBRE ---//
if(isset($_GET['action']) && $_GET['action'] == "suppression")
{   // $contenu .= $_GET['id_membre']
    $resultat = executeRequete("SELECT * FROM membre WHERE id_membre=$_GET[id_membre]");
    $contenu .= '<div class="validation">Suppression du membre : ' . $_GET['id_membre'] . '</div>';
    executeRequete("DELETE FROM membre WHERE id_membre=$_GET[id_membre]");
    $_GET['action'] = 'affichage';
}
//--- ENREGISTREMENT MEMBRE ---//
if(!empty($_POST))
{   // debug($_POST);
    
    foreach($_POST as $indice => $valeur)
    {
        $_POST[$indice] = htmlEntities(addSlashes($valeur));
    }
    executeRequete("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) values ('$_POST[id_membre]', '$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '$_POST[ville]', '$_POST[code_postal]', '$_POST[adresse]',  '$_POST[statut]')");
    $contenu .= '<div class="validation">Le neauvau membre a été ajouté</div>';
    $_GET['action'] = 'affichage';
}
//--- LIENS PRODUITS ---//
$contenu .= '<a href="?action=affichage"><div class="yes">Affichage des membres</div></a><br>';
$contenu .= '<a href="?action=ajout"><div class="No">Ajoute d\'un nouveau membre</div></a><br><br><hr><br>';
//--- AFFICHAGE PRODUITS ---//
if(isset($_GET['action']) && $_GET['action'] == "affichage")
{
    $resultat = executeRequete("SELECT * FROM membre");
     
    $contenu .= '<h2> Affichage des membres </h2>';
    $contenu .= 'Nombre de produit(s) dans la boutique : ' . $resultat->num_rows;
    $contenu .= '<table border="1" cellpadding="5"><tr>';
     
    while($colonne = $resultat->fetch_field())
    {    
        $contenu .= '<th>' . $colonne->name . '</th>';
    }
    $contenu .= '<th>Supression</th>';
    $contenu .= '</tr>';
 
    while ($ligne = $resultat->fetch_assoc())
    {
        $contenu .= '<tr>';
        foreach ($ligne as $indice => $information)
        {
                $contenu .= '<td>' . $information . '</td>';
        }
        $contenu .= '<td><a href="?action=suppression&id_membre=' . $ligne['id_membre'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));"><img src="../photo/delete.png" height="30"></a></td>';
        $contenu .= '</tr>';
    }
    $contenu .= '</table><br><hr><br>';
}
//--------------------------------- AFFICHAGE HTML ---------------------------------//
require_once("../inc/haut.inc.php");
echo $contenu;
if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification'))
{
    if(isset($_GET['id_produit']))
    {
        $resultat = executeRequete("SELECT * FROM membre WHERE id_membre=$_GET[id_membre]");
        $membre_actuel = $resultat->fetch_assoc();
    }
    echo '
    <h1> Formulaire Membres </h1>
    <form method="post" enctype="multipart/form-data" action="">
     
        <input type="hidden" id="id_membre" name="id_membre" value="'; if(isset($membre_actuel['id_membre'])) echo $membre_actuel['id_membre']; echo '">
             
        <label for="pseudo">pseudo</label><br>
        <input type="text" id="pseudo" name="pseudo" placeholder="le pseudo de membre" value="'; if(isset($membre_actuel['pseudo'])) echo $membre_actuel['pseudo']; echo '"><br><br>
 
        <label for="mdp">mdp</label><br>
        <input type="text" id="mdp" name="mdp" placeholder="le mdp de membre" value="'; if(isset($membre_actuel['mdp'])) echo $membre_actuel['mdp']; echo '" ><br><br>
 
        <label for="nom">nom</label><br>
        <input type="text" id="nom" name="nom" placeholder="le nom du membre" value="'; if(isset($membre_actuel['nom'])) echo $membre_actuel['nom']; echo '" > <br><br>
 
        <label for="prenom">prenom</label><br>
        <input type="text" name="prenom" id="prenom" placeholder="le prenom du membre" value="'; if(isset($membre_actuel['prenom'])) echo $membre_actuel['prenom']; echo '"> <br><br>
         
        <label for="email">email</label><br>
        <input type="text" name="email" id="email" placeholder=" email du membre" value="'; if(isset($membre_actuel['email'])) echo $membre_actuel['email']; echo '"> <br><br>
                 
        <label for="civilite">Civilité</label><br>
        <input name="civilite" value="m" checked="" type="radio">Homme
        <input name="civilite" value="f" type="radio">Femme <br><br>'; if(isset($membre_actuel['civilite'])) echo $membre_actuel['civilite'];  echo ' <br><br>

        <label for="ville">ville</label><br>
        <input type="text" name="ville" id="ville" placeholder="la ville du membre" value="'; if(isset($membre_actuel['ville'])) echo $membre_actuel['ville']; echo '"> <br><br>
         
        <label for="adresse">adresse</label><br>
        <input type="text" name="adresse" id="adresse" placeholder=" adresse du membre" value="'; if(isset($membre_actuel['adresse'])) echo $membre_actuel['adresse']; echo '"> <br><br>';
        
        
        echo '
        <label for="code_postal">code_postal</label><br>
        <input type="text" id="code_postal" name="code_postal" placeholder="le code_postal du membre"  value="'; if(isset($produit_actuel['code_postal'])) echo $produit_actuel['code_postal']; echo '"><br><br>
         
        <label for="statut">statut</label><br>
        <input type="text" id="statut" name="statut" placeholder="le statut du membre"  value="'; if(isset($produit_actuel['statut'])) echo $produit_actuel['statut']; echo '"><br><br>
         
        <input type="submit" value="'; echo ucfirst($_GET['action']) . ' du membre">
    </form>';
}
require_once("../inc/bas.inc.php"); ?>