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

<form method="post" action="">
    <table class='table'>
        <tr>
            <th colspan="4">Proposition</th>
            <th>Résultat</th>
        </tr>
        <?php

        $history = new DOMDocument();
        if (!isset($_SESSION['history'])){
            echo "Création de l'historique";
            for ($i = 0; $i < $_SESSION['masterMind']->getMaxTries(); $i++){
                $tr = $history->createElement("tr");
                $tr->setAttribute("id", $i+1);
                for ($j = 0; $j < 5; $j++){
                    $td = $history->createElement("td");
                    $tr->appendChild($td);
                }
                $history->appendChild($tr);
            }
            $_SESSION['history'] = $history->saveHTML();
        } else {
            echo "Historique déjà existant";
            $masterMind = $_SESSION['masterMind'];
            $history->loadHTML($_SESSION['history']);
            for ($i = 0; $i < 4; $i++){
                $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($form->getValue("emplacement" . $i+1)));
            }
        }
        echo $history->saveHTML();

        ?>


        <!-- <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>
                <span class="pion blanc">&bull;</span>
                <span class="pion rouge">&bull;</span>
            </td>
        </tr> -->
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
            
            if ($masterMind->getTry() != 0){
                for ($i = 0; $i < 4; $i++){
                    $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($form->getValue("emplacement" . $i+1)));
                    $_SESSION['history'] = $history->saveHTML();
                    $code = $code . $form->getValue("emplacement" . $i+1);
                }
                var_dump($masterMind->checkCode($code));
            }
            
            $masterMind->incrementTry();

            $_SESSION['code'] = $code;
            $_SESSION['masterMind'] = $masterMind;
            var_dump("Voici le code entre " . $code);
            
            ?>
        </tr>
    </table>
    <?php $form->submit(); ?>
</form>