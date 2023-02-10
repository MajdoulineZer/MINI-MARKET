<?php
require_once("../inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- VERIFICATION ADMIN ---//
if(!internauteEstConnecteEtEstAdmin())
{
    header("location:../connexion.php");
    exit();
}
 
//--- SUPPRESSION PRODUIT ---//
if(isset($_GET['action']) && $_GET['action'] == "suppression")
{   // $contenu .= $_GET['id_produit']
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit=$_GET[id_produit]");
    $produit_a_supprimer = $resultat->fetch_assoc();
    $chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . $produit_a_supprimer['photo'];
    if(!empty($produit_a_supprimer['photo']) && file_exists($chemin_photo_a_supprimer)) unlink($chemin_photo_a_supprimer);
    $contenu .= '<div class="validation">Suppression du produit : ' . $_GET['id_produit'] . '</div>';
    executeRequete("DELETE FROM produit WHERE id_produit=$_GET[id_produit]");
    $_GET['action'] = 'affichage';
}
//--- ENREGISTREMENT PRODUIT ---//
if(!empty($_POST))
{   // debug($_POST);
    $photo_bdd = ""; 
    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        $photo_bdd = $_POST['photo_actuelle'];
    }
    if(!empty($_FILES['photo']['name']))
    {   // debug($_FILES);
        $nom_photo = $_POST['reference'] . '_' .$_FILES['photo']['name'];
        $photo_bdd = RACINE_SITE . "photo/$nom_photo";
        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . "/photo/$nom_photo"; 
        copy($_FILES['photo']['tmp_name'],$photo_dossier);
    }
    foreach($_POST as $indice => $valeur)
    {
        $_POST[$indice] = htmlEntities(addSlashes($valeur));
    }
    executeRequete("REPLACE INTO produit (id_produit, reference, categorie, titre, description, couleur, photo, prix, stock) values ('$_POST[id_produit]', '$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$photo_bdd',  '$_POST[prix]',  '$_POST[stock]')");
    $contenu .= '<div class="validation">Le produit a été ajouté</div>';
    $_GET['action'] = 'affichage';
}
//--- LIENS PRODUITS ---//
$contenu .= '<a href="?action=affichage" ><div class="Affiche">Affichage des produits</div></a><br>';
$contenu .= '<a href="?action=ajout"><div class="Ajout">Ajout d\'un produit</div></a><br><br><hr><br>';
//--- AFFICHAGE PRODUITS ---//
if(isset($_GET['action']) && $_GET['action'] == "affichage")
{
    $resultat = executeRequete("SELECT * FROM produit");
     
    $contenu .= '<h2> Affichage des produits </h2>';
    $contenu .= 'Nombre de produit(s) dans la boutique : ' . $resultat->num_rows;
    $contenu .= '<table border="1" cellpadding="5"><tr>';
     
    while($colonne = $resultat->fetch_field())
    {    
        $contenu .= '<th>' . $colonne->name . '</th>';
    }
    $contenu .= '<th>Modification</th>';
    $contenu .= '<th>Supression</th>';
    $contenu .= '</tr>';
 
    while ($ligne = $resultat->fetch_assoc())
    {
        $contenu .= '<tr>';
        foreach ($ligne as $indice => $information)
        {
            if($indice == "photo")
            {
                $contenu .= '<td><img src="' . $information . '" ="90" height="90"></td>';
            }
            else
            {
                $contenu .= '<td>' . $information . '</td>';
            }
        }
        $contenu .= '<td><a href="?action=modification&id_produit=' . $ligne['id_produit'] .'"><img src="/site/photo/edit.png"></a></td>';
        $contenu .= '<td><a href="?action=suppression&id_produit=' . $ligne['id_produit'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));"><img src="/site/photo/delete.png"></a></td>';
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
        $resultat = executeRequete("SELECT * FROM produit WHERE id_produit=$_GET[id_produit]");
        $produit_actuel = $resultat->fetch_assoc();
    }
    echo '
    <h1> Formulaire Produits </h1>
    <form method="post" enctype="multipart/form-data" action="">
     
        <input type="hidden" id="id_produit" name="id_produit" value="'; if(isset($produit_actuel['id_produit'])) echo $produit_actuel['id_produit']; echo '">
             
        <label for="reference">reference</label><br>
        <input type="text" id="reference" name="reference" placeholder="la référence de produit" value="'; if(isset($produit_actuel['reference'])) echo $produit_actuel['reference']; echo '"><br><br>
 
        <label for="categorie">categorie</label><br>
        <input type="text" id="categorie" name="categorie" placeholder="la categorie de produit" value="'; if(isset($produit_actuel['categorie'])) echo $produit_actuel['categorie']; echo '" ><br><br>
 
        <label for="titre">titre</label><br>
        <input type="text" id="titre" name="titre" placeholder="le titre du produit" value="'; if(isset($produit_actuel['titre'])) echo $produit_actuel['titre']; echo '" > <br><br>
 
        <label for="description">description</label><br>
        <textarea name="description" id="description" placeholder="la description du produit">'; if(isset($produit_actuel['description'])) echo $produit_actuel['description']; echo '</textarea><br><br>
         
        <label for="couleur">couleur</label><br>
        <input type="text" id="couleur" name="couleur" placeholder="la couleur du produit"  value="'; if(isset($produit_actuel['couleur'])) echo $produit_actuel['couleur']; echo '"> <br><br>
 
        
         
        <label for="photo">photo</label><br>
        <input type="file" id="photo" name="photo"><br><br>';
        if(isset($produit_actuel))
        {
            echo '<i>Vous pouvez uplaoder une nouvelle photo si vous souhaitez la changer</i><br>';
            echo '<img src="' . $produit_actuel['photo'] . '"  widht="130" height="130"><br>';
            echo '<input type="hidden" name="photo_actuelle" value="' . $produit_actuel['photo'] . '"><br>';
        }
         
        echo '
        <label for="prix">prix</label><br>
        <input type="text" id="prix" name="prix" placeholder="le prix du produit"  value="'; if(isset($produit_actuel['prix'])) echo $produit_actuel['prix']; echo '"><br><br>
         
        <label for="stock">stock</label><br>
        <input type="text" id="stock" name="stock" placeholder="le stock du produit"  value="'; if(isset($produit_actuel['stock'])) echo $produit_actuel['stock']; echo '"><br><br>
         
        <input type="submit" value="'; echo ucfirst($_GET['action']) . ' du produit">
    </form>';
}
require_once("../inc/bas.inc.php"); ?>