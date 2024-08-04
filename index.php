<?php

require 'MasterMind.php';
require 'Form.php';

echo session_save_path();
session_start();

if (!isset($_SESSION['masterMind'])){
    $masterMind = new MasterMind();
    $_SESSION['masterMind'] = $masterMind;
    $_SESSION['secretCode'] = $masterMind->generateSecretCode();
}

$secretCode = $_SESSION['secretCode'];
var_dump("Code généré aléatoirement");
var_dump($secretCode);

$form = new Form($_POST);

?>

<style>
    span.pion {
        font-size: 2.5em;
        line-height: 20px;
        margin: 0 -2px;
        text-shadow: 0 0 2px black;
    }
    .pion.blanc {
        color: white;
    }
    .pion.rouge {
        color: red;
    }

</style>

<form method="post" action="">
    <table class='table'>
        <tr>
            <th colspan="4">Proposition</th>
            <th>Résultat</th>
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
            <th colspan="5">A vous de jouer !</th>
        </tr>
        <tr>
            <?php 
            $masterMind = $_SESSION['masterMind'];
            $history = new DOMDocument();
            $history->loadHTML($_SESSION['history']);
            $code = "";
            echo $form->select();
            
            if ($masterMind->getTry() != 0 & $masterMind->getTry() < $masterMind->getMaxTries()){
                for ($i = 0; $i < 4; $i++){
                    $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($form->getValue("emplacement" . $i+1)));
                    $code = $code . $form->getValue("emplacement" . $i+1);
                }

                var_dump($masterMind->checkCode($code));

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
        </tr>
    </table>
    <?php 
        if ($masterMind->getTry() - 1 == $masterMind->getMaxTries()){ // $masterMind->getTry() - 1 car on incrémente le nombre d'essaies avant de vérifier si on a atteint le nombre maximum d'essaies
            echo "Partie terminée, vous avez perdu.";
            // Ajouter bouton pour relancer une partie
        } else if ($masterMind->getWin()){
            echo "Partie terminée, vous avez gagné.";
            // Ajouter bouton pour relancer une partie
        } else {
            echo "Il vous reste " . ($masterMind->getMaxTries() - $masterMind->getTry() + 1) . " essaie(s). <br>";
            $form->submit();
        }
        ?>
</form>