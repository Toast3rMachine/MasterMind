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

        session_start();

        if (!isset($_SESSION['masterMind'])){
            $masterMind = new MasterMind();
            $_SESSION['masterMind'] = $masterMind;
            $_SESSION['secretCode'] = $masterMind->generateSecretCode();
        }

        $secretCode = $_SESSION['secretCode'];

        $form = new Form($_POST);

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
            <form method="post" action="">
                <table class='table'>
                    <tr>
                        <th colspan="4" class='tab'>Proposition</th>
                        <th class='tab'>Résultat</th>
                    </tr>
                    <?php

                    $history = new DOMDocument();
                    if (!isset($_SESSION['history'])){
                        for ($i = 0; $i < $_SESSION['masterMind']->getMaxTries(); $i++){
                            $tr = $history->createElement("tr");
                            
                            $tr->setAttribute("id", $i+1);
                            for ($j = 0; $j < 5; $j++){
                                $td = $history->createElement("td");
                                $tr->appendChild($td);
                            }
                            for ($j = 0; $j < 2; $j++){
                                $span = $history->createElement("span");
                                $tr->getElementsByTagName("td")[4]->appendChild($span);
                                if ($j == 0){
                                    $span->setAttribute("class", "pion blanc");
                                } else {
                                    $span->setAttribute("class", "pion rouge");
                                }
                            }
                            $history->appendChild($tr);
                        }
                        $_SESSION['history'] = $history->saveHTML();
                    } else {
                        $masterMind = $_SESSION['masterMind'];
                        $code = "";
                        $history->loadHTML($_SESSION['history']);

                        for ($i = 0; $i < 4; $i++){ // Parcours de la liste <td></td>
                            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->setAttribute("class", "number");
                            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($form->getValue("emplacement" . $i+1)));
                            $code = $code . $form->getValue("emplacement" . $i+1);
                        }

                        for ($i = 0; $i < 2; $i++){
                            for ($j = 0; $j < $masterMind->checkCode($code)[$i]; $j++){
                                    $history->getElementById($masterMind->getTry())->getElementsByTagName("span")[$i]->appendChild($history->createTextNode("•"));
                                }
                        }

                    }
                    echo $history->saveHTML();

                    ?>
                    <tr>
                        <th colspan="5" class='tab'>A vous de jouer !</th>
                    </tr>
                </table>
                <div class="select option">
                    <?php 
                    $masterMind = $_SESSION['masterMind'];
                    $history = new DOMDocument();
                    $history->loadHTML($_SESSION['history']);
                    $code = "";
                    echo $form->select();
                    
                    if ($masterMind->getTry() != 0 & $masterMind->getTry() < $masterMind->getMaxTries()){
                        for ($i = 0; $i < 4; $i++){
                            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->setAttribute("class", "number");
                            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($form->getValue("emplacement" . $i+1)));
                            $code = $code . $form->getValue("emplacement" . $i+1);
                        }

                        for ($i = 0; $i < 2; $i++){
                            for ($j = 0; $j < $masterMind->checkCode($code)[$i]; $j++){
                                    $history->getElementById($masterMind->getTry())->getElementsByTagName("span")[$i]->appendChild($history->createTextNode("•"));
                                }
                        }

                        $_SESSION['history'] = $history->saveHTML();
                        $_SESSION['code'] = $code;
                    }

                    $masterMind->incrementTry();

                    $_SESSION['masterMind'] = $masterMind;
                    
                    ?>
                </div>
                <?php 
                    if ($masterMind->getTry() - 1 == $masterMind->getMaxTries()){ // $masterMind->getTry() - 1 car on incrémente le nombre d'essaies avant de vérifier si on a atteint le nombre maximum d'essaies
                        echo "<p class='information'>Partie terminée, vous avez perdu.</p>";
                        // Ajouter bouton pour relancer une partie
                    } else if ($masterMind->getWin()){
                        echo "<p class='information'>Partie terminée, vous avez gagné.</p>";
                        // Ajouter bouton pour relancer une partie
                    } else {
                        echo "<p class='information'>Il vous reste " . ($masterMind->getMaxTries() - $masterMind->getTry() + 1) . " essaie(s).</p> <br>";
                        $form->submit();
                    }
                    ?>
            </form>
        </main>
    </body>
</html>





