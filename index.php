<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MasterMind</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style/global.css" rel="stylesheet" />
        <link href="style/index.css" rel="stylesheet" />
        <?php

        require 'src/MasterMind.php';
        require 'src/Form.php';

        session_id('masterMind');
        if (!isset($_SESSION)) session_start();

        // Réinitialise la session
        if (isset($_POST['Rejouer'])){
            $options = $_SESSION['options']; // Sauvegarde des options afin que l'utilisateur puisse relancer une partie avec les mêmes paramètres
            session_destroy();
            session_start();
            $_SESSION['options'] = $options;
        }

        // Initialisation de la session
        if (!isset($_SESSION['masterMind']) || isset($_SESSION['modification'])){ // Si la session n'existe pas ou si elle a été modifiée
            // On regénère un code secret quand on change les paramètres pour éviter la triche sur une même partie
            $masterMind = new MasterMind();
            $_SESSION['masterMind'] = $masterMind;
            $_SESSION['secretCode'] = $masterMind->generateSecretCode();
            if (isset($_SESSION['modification'])) {
                unset($_SESSION['modification']); // Supprime la modification pour ne pas la réafficher
                unset($_SESSION['history']); // Supprime l'historique pour le regénérer avec les bons paramètres
            }
        }
        
        $secretCode = $_SESSION['secretCode']; // Récupération du code secret
        $form = new Form($_POST); // Création du formulaire

        // Condition du Post-Redirect-Get pour éviter le renvoi du formulaire
        if (isset($_POST['Submit'])) { // Si le bouton valider est cliqué
            $history = new DOMDocument();
            $masterMind = $_SESSION['masterMind'];

            $emplacementTab = array(); 
            for ($i = 0; $i < $masterMind->getSize(); $i++){ // Parcours de la liste <select></select>, on a $masterMind->getSize() chiffres à deviner
                $emplacementTab[$i] = $_POST['emplacement' . $i+1]; // Récupération des valeurs des emplacements
            }
            // if (count(array_unique($emplacementTab)) == count($emplacementTab)){ // Vérifie si les chiffres sont différents

                $masterMind = $_SESSION['masterMind'];
                $masterMind->incrementTry();
                $_SESSION['masterMind'] = $masterMind;
                
                $form->updateHistory($history, $form, $emplacementTab); // Mise à jour de l'historique
                header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 ); // Redirection pour éviter le renvoi du formulaire
                exit(); // Arrêt du script
            // } else {
            //     $_SESSION['erreur'] = true; // Si les chiffres ne sont pas différents, on enregistre une erreur
            // }
        }

        ?>
    </head>
    <body>
        <main>
            <h1 class='title'>MasterMind</h1>
            <h4>Regles du jeu :</h4>
            <div class='rules'>
                <p>L'ordinateur choisi une combinaison à 4 chiffres entre 1 et 6. </p>
                <p>
                    Le joueur propose une combinaison. 
                    L'ordinateur lui, retourne un code sous forme de pion sans préciser quel chiffre corresepond à quel pion : un pion rouge par chiffre juste mais mal placé, et un pion blanc par chiffre bien placé. 
                </p>
                <p>Lorsque le code est 4 pions blanc le joueur a gagné et peut relancer une partie. </p>
            </div>
            <a href="view/settings.php"><button class='option'>Paramètres</button></a>
            <form method="post" action="index.php">
                <table class='table'>
                    <tr>
                        <?php 
                        $size = $_SESSION['masterMind']->getSize();
                        echo "<th colspan='$size' class='tab' id='proposition'>Proposition</th>";
                        ?>
                        <th class='tab' id='resultat'>Résultat</th>
                    </tr>
                    <?php

                    $history = new DOMDocument();

                    if (!isset($_SESSION['history'])){
                        $form->generateHistory($_SESSION['masterMind']->getMaxTries(), $_SESSION['masterMind']->getSize()); // Génère l'historique
                        echo $form->displayHistory(); // Affiche l'historique
                    } else {
                        echo $form->displayHistory();
                    }
                    
                    ?>
                    <tr>
                        <?php 
                        $size = $_SESSION['masterMind']->getSize()+1;
                        echo "<th colspan='$size' class='tab'>";
                        ?>
                            <?php
                            $masterMind = $_SESSION['masterMind'];
                            if ($masterMind->getWin()){ // Affiche un message si le joueur a gagné
                                echo "Partie terminée, vous avez gagné.";
                            } else if ($masterMind->getLose()){ // Affiche un message si le joueur a perdu et le code secret
                                echo "Partie terminée, vous avez perdu. <br>";
                                echo "<p class='information'>Le code à deviner était : " . $masterMind->getSecretCode() . "</p>";
                            } else {
                                echo "A vous de jouer !";
                            }
                            ?>
                            
                        </th>
                    </tr>
                </table>
                <?php
                if (isset($_SESSION['erreur'])){ // Affiche une erreur si les chiffres ne sont pas différents
                    echo "<p class='error'>Veuillez choisir un chiffre différent pour chaque emplacement.</p>";
                    unset($_SESSION['erreur']); // Supprime l'erreur pour ne pas la réafficher
                }
                ?>
                <div class="select option">
                    <?php
                    echo $form->select($_SESSION['masterMind']->getSize()); // Affiche le formulaire de sélection des chiffres
                    ?>
                </div>
                <?php 
                    if ($masterMind->getWin() || $masterMind->getLose()){ // Affiche le bouton rejouer si la partie est terminée
                        echo "<p class='information'>Voulez-vous relancer une partie ?</p> <br>";
                        echo $form->replay();
                    } else { // Affiche le nombre d'essais restants et le bouton valider
                        echo "<p class='information'>Il vous reste " . ($masterMind->getMaxTries() - $masterMind->getTry()) . " essaie(s).</p> <br>";
                        echo $form->submit();
                    }
                    ?>
            </form>
        </main>
    </body>
</html>