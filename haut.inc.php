<!Doctype html>
<html>
    <head>
        <title>Mon Site</title>
        <link rel="stylesheet" href="<?php echo RACINE_SITE; ?>inc/css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/9044f3b38c.js" crossorigin="anonymous"></script>

    </head>
    <body>    
        
    <header>     
        <div class="conteneur">
       
        <div class="logo">
        <img src="/site/photo/Logo1.png" >    
                  
        </div> 
        <div class="Rectangle">
            <marquee behavior="scroll" direction="left" speed="0,3">
            <ul class="scrolling">
            <li><a href="https://www.amazon.fr"><img src="/site/photo/SALE.jpg" ></a></li>
            <li><a href="https://www.amazon.fr"><img src="/site/photo/Adidas.jpg" ></a></li>
            <li><a href="https://www.amazon.fr"><img src="/site/photo/nike.jpg" ></a></li>
            <li><a href="https://www.amazon.fr"><img src="/site/photo/moulinex.jpg" ></a></li>
            <li><a href="https://www.amazon.fr"><img src="/site/photo/ikea.jpg" ></a></li>
            <li><a href="https://www.amazon.fr"><img src="/site/photo/comode.jpg" ></a></li>
            </ul>
           </marquee>
                    </div> 
                <nav>
                    <div class="hey">
                    <?php 
                    if(internauteEstConnecteEtEstAdmin())
                    {
                        echo '<a href="' . RACINE_SITE . 'admin/gestion_membre.php">Gestion des membres</a>';
                        echo '<a href="' . RACINE_SITE . 'admin/gestion_commande.php">Gestion des commandes</a>';
                        echo '<a href="' . RACINE_SITE . 'admin/gestion_boutique.php">Gestion de la boutique</a>';
                    }
                    if(internauteEstConnecte())
                    {
                        echo '<a href="' . RACINE_SITE . 'boutique.php">Accès à la boutique</a>';
                        echo '<a href="' . RACINE_SITE . 'profil.php"><i class="fa-solid fa-user"></i></a>';
                        echo '<a href="' . RACINE_SITE . 'panier.php"><i class="fas fa-shopping-cart"></i></a>';
                        echo '<a href="' . RACINE_SITE . 'connexion.php?action=deconnexion"><i class="fa-solid fa-right-from-bracket"></i></a>';
                    }
                    else
                    {
                        echo '<a href="' . RACINE_SITE . 'inscription.php">Inscription</a>';
                        echo '<a href="' . RACINE_SITE . 'connexion.php">Connexion</a>';
                        echo '<a href="' . RACINE_SITE . 'boutique.php">Accès à la boutique</a>';
                        echo '<a href="' . RACINE_SITE . 'panier.php"><i class="fas fa-shopping-cart"></i></a>';
                    }
                    ?>
                    
                    </div>
                    </div>
                    
                </nav>
              
                 
            </div>
            
        </header>
       
        <section>
            