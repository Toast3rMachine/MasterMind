<?php

//Création de la class Form
class Form{

    private $data;

    public function __construct($data = array()){
        $this->data = $data;
    }

    //Méthode d'affichage de l'historique
    public function displayHistory(){
        echo $_SESSION['history'];
    }

    //Méthode de génération de l'historique
    public function generateHistory($numberOfCells){
        $history = new DOMDocument();
        for ($i = 0; $i < $numberOfCells; $i++){
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
    }

    //Méthode de mise à jour de l'historique
    public function updateHistory($history, $form, $emplacementTab){
        $masterMind = $_SESSION['masterMind'];
        $proposition = "";
        $history->loadHTML($_SESSION['history']);

        for ($i = 0; $i < 4; $i++){ // Parcours de la liste <td></td>
            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->setAttribute("class", "number");
            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($emplacementTab[$i]));
            $proposition = $proposition . $form->getValue("emplacement" . $i+1);
        }

        for ($i = 0; $i < 2; $i++){
            for ($j = 0; $j < $masterMind->checkCode($proposition)[$i]; $j++){
                    $history->getElementById($masterMind->getTry())->getElementsByTagName("span")[$i]->appendChild($history->createTextNode("•"));
                }
        }
        $_SESSION['proposition'] = $proposition;
        $_SESSION['history'] = $history->saveHTML();
    }

    //Méthode de sélection des chiffres
    public function select(){
        for($i = 0; $i < 4; $i++){
            echo "<td>";
            echo "<select name= emplacement" . $i+1 . ">";
            for($j = 1; $j <= 6; $j++){
                echo "<option value=".$j.">".$j."</option>";
            }
            echo "</select>";
            echo "</td>";
        }
    }

    //Méthode de relance du jeux
    public function replay(){
        return "<td><input type='submit' value='Rejouer' name='Rejouer'></td>";
    }

    //Méthode de validation
    public function submit(){
        return "<td><input type='submit' value='Valider' name='Submit'></td>";
    }

    public function getValue($index){
        return isset($this->data[$index]) ? $this->data[$index] : null;
    }

}
?>