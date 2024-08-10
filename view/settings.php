<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Paramètres</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../style/global.css" rel="stylesheet" />
        <link href="../style/settings.css" rel="stylesheet" />
        <?php

        require '../src/Options.php';
        require '../src/MasterMind.php';

        session_id('masterMind');
        if (!isset($_SESSION)) session_start();

        $masterMind = $_SESSION['masterMind'];
        if (!isset($_SESSION['options'])){ // Si la session n'existe pas, on la crée
            $options = new Options();
            $_SESSION['options'] = $options;
        } else {
            $options = $_SESSION['options'];
        }

        if (isset($_POST['Submit'])) { // Si le bouton valider est cliqué

            $newSize = $_POST['size'];
            $newMaxTries = $_POST['maxTries'];

            if ($newSize != $options->getSize() || $newMaxTries != $options->getMaxTries()){

                $options->applySettings($newSize, $newMaxTries);
                $_SESSION['options'] = $options;

                $_SESSION['modification'] = true; // Si une modification a été effectuée

                header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 ); // Redirection pour éviter le renvoi du formulaire
                exit(); // Arrêt du script
            }
            $_SESSION['erreur'] = true; // Si les chiffres ne sont pas différents, on enregistre une erreur

        }
        ?>
    </head>
    <body>
        <main>
            <h1 class='title'>MasterMind</h1>
            <h4>Paramètres</h4>
            <div class='information'>
                <p>Paramètres par défaut : </p>
                <p>
                    Nombre de chiffres à deviner : 4 <br>
                    Nombre d'essais maximum : 10
                </p>
                <?php
                $options = $_SESSION['options'];
                echo $options->currentSettings(); // Affiche les paramètres actuels
                if (isset($_SESSION['erreur'])){ // On vérifie si les changements appliqués sont identiques aux paramètres actuels
                    echo "<p class='important'>Aucun changement à apporter.</p>";
                    unset($_SESSION['erreur']);
                } else if (isset($_SESSION['modification'])){ // Si une modification a été effectuée
                    echo "<p class='important'>Les modifications ont bien été prises en compte.</p>";
                } else { // Sinon on affiche un message pour appliquer les modifications
                    echo "<p class='important'>Pensez à appliquer les modifications avant de revenir au jeu. </p>";
                }
                ?>
            </div>
            <form method="post" action="settings.php">
                <?php // Affichage des deux options de paramètrages et du bouton valider
                echo $options->changeMaxSize();
                echo $options->changeMaxTries();
                echo $options->submit();
                ?>
            </form>
            <a href="../index.php"><button class='option'>Retour</button></a>
        </main>
    </body>
</html>