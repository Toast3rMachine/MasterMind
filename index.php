<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MasterMind</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style/style.css" rel="stylesheet" />
        <?php

        require 'src/MasterMind.php';
        require 'src/Form.php';

        session_id('masterMind');
        if (!isset($_SESSION)) session_start();

        // Réinitialise la session
        if (isset($_POST['Rejouer'])){
            session_destroy();
            session_start();
        }

        // Initialisation de la session
        if (!isset($_SESSION['masterMind'])){
            $masterMind = new MasterMind();
            $_SESSION['masterMind'] = $masterMind;
            $_SESSION['secretCode'] = $masterMind->generateSecretCode();
        }
        
        $secretCode = $_SESSION['secretCode'];
        $form = new Form($_POST);

        if (isset($_POST['Submit'])) {
            $history = new DOMDocument();

            $emplacementTab = array();
            for ($i = 0; $i < 4; $i++){
                $emplacementTab[$i] = $_POST['emplacement' . $i+1];
            }
            if (count(array_unique($emplacementTab)) == count($emplacementTab)){

                $masterMind = $_SESSION['masterMind'];
                $masterMind->incrementTry();
                $_SESSION['masterMind'] = $masterMind;
                
                $form->updateHistory($history, $form, $emplacementTab);
                header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
                exit();
            } else {
                $_SESSION['erreur'] = true;
            }
        }

        ?>
    </head>
    <body>
        <main>
            <h1 class='title'>MasterMind</h1>
            <h4>Regles du jeu :</h4>
            <div class='rules'>
                <p>L'ordinateur choisi une combinaison a 4 chiffres entre 1 et 6. </p>
                <p>
                    Le joueur propose une combinaison. 
                    L'ordinateur lui retourne un code sous forme de pion sans préciser quel chiffre corresepond a quel pion : un pion rouge par chiffre juste mais mal placé, et un pion blanc par chiffre bien placé. 
                </p>
                <p>Lorsque le code est 4 pions blanc le joueur a gagné et peut relancer une partie. </p>
            </div>
            <form method="post" action="index.php">
                <table class='table'>
                    <tr>
                        <th colspan="4" class='tab'>Proposition</th>
                        <th class='tab'>Résultat</th>
                    </tr>
                    <?php

                    $history = new DOMDocument();

                    if (!isset($_SESSION['history'])){
                        $form->generateHistory($_SESSION['masterMind']->getMaxTries());
                        echo $form->displayHistory();
                    } else {
                        echo $form->displayHistory();
                    }
                    
                    ?>
                    <tr>
                        <th colspan="5" class='tab'>
                            <?php
                            $masterMind = $_SESSION['masterMind'];
                            if ($masterMind->getWin()){
                                echo "Partie terminée, vous avez gagné.";
                            } else if ($masterMind->getLose()){
                                echo "Partie terminée, vous avez perdu. <br>";
                                echo "<p class='information'>Le code à deviner était : " . $masterMind->getSecretCode() . "</p>";
                            } else {
                                echo "A vous de jouer !";
                            }
                            ?>
                            
                        </th>
                    </tr>
                </table>
                <p class="error">
                    <?php
                    if (isset($_SESSION['erreur'])){
                        echo "Veuillez choisir un chiffre différent pour chaque emplacement.";
                        unset($_SESSION['erreur']);
                    }
                    ?>
                </p>
                <div class="select option">
                    <?php
                    echo $form->select();                    
                    ?>
                </div>
                <?php 
                    if ($masterMind->getWin() || $masterMind->getLose()){
                        echo "<p class='information'>Voulez-vous relancer une partie ?</p> <br>";
                        echo $form->replay();
                    } else {
                        echo "<p class='information'>Il vous reste " . ($masterMind->getMaxTries() - $masterMind->getTry()) . " essaie(s).</p> <br>";
                        echo $form->submit();
                    }
                    ?>
            </form>
        </main>
    </body>
</html>