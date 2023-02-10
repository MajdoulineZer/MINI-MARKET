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
{   // $contenu .= $_GET['id_details_commande']
    $resultat = executeRequete("SELECT * FROM details_commande WHERE id_details_commande=$_GET[id_details_commande]");
    $commande_a_supprimer = $resultat->fetch_assoc();
    $chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . $$commande_a_supprimer['photo'];
    if(!empty($$commande_a_supprimer['photo']) && file_exists($chemin_photo_a_supprimer)) unlink($chemin_photo_a_supprimer);
    $contenu .= '<div class="validation">Suppression du produit : ' . $_GET['id_details_commande'] . '</div>';
    executeRequete("DELETE FROM details_commande WHERE id_details_commande=$_GET[id_details_commande]");
    $_GET['action'] = 'affichage';
}

//--- LIENS PRODUITS ---//
$contenu .= '<a href="?action=affichage"><div class="comm">Affichage des commandes</div> </a><br>';
//--- AFFICHAGE PRODUITS ---//
if(isset($_GET['action']) && $_GET['action'] == "affichage")
{
    $resultat = executeRequete("SELECT membre.pseudo, membre.adresse, membre.ville, membre.code_postal, produit.titre, produit.photo, details_commande.quantite, commande.date_enregistrement, commande.montant
    FROM membre
    INNER JOIN commande ON membre.id_membre = commande.id_membre
    INNER JOIN details_commande ON commande.id_commande = details_commande.id_commande
    INNER JOIN produit ON details_commande.id_produit = produit.id_produit");
    
     
    $contenu .= '<h2> Affichage des commandes </h2>';
    $contenu .= 'Nombre de commande(s) dans la boutique : ' . $resultat->num_rows;
    $contenu .= '<table border="1" cellpadding="5"><tr>';
   
    while($colonne = $resultat->fetch_field())
    {    
        $contenu .= '<th>' . $colonne->name . '</th>';
    }
    $contenu .= '</tr>';
 
    while ($ligne = $resultat->fetch_assoc())
    {
        $contenu .= '<tr>';
        foreach ($ligne as $indice => $information)
        {
            if($indice == "photo")
            {
                $contenu .= '<td><img src="' . $information . '" ="70" height="70"></td>';
            }
            else
            {
                $contenu .= '<td>' . $information . '</td>';
            }
        }
       
    }
    $contenu .= '</table><br><hr><br>';
}
//--------------------------------- AFFICHAGE HTML ---------------------------------//
require_once("../inc/haut.inc.php");
echo $contenu;
require_once("../inc/bas.inc.php");
?>