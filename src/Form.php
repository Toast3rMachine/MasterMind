<?php

/**
 * Class Form
 * Permet de créer un formulaire.
 */
class Form{

    /**
     * @var array Données utilisées par le formulaire
     */
    private array $data;

    /**
     * @param array $data
     * 
     * Constructeur de la classe
     */
    public function __construct(array $data = array()){
        $this->data = $data;
    }

    /**
     * @return string
     * 
     * Retourne l'historique afin de l'afficher
     */
    public function displayHistory(): string{
        return $_SESSION['history'];
    }

    /**
     * @param int $numberOfLines
     * @param int $numberOfCells
     * 
     * @return void
     * 
     * Génère l'historique du jeu quand celui-ci n'est pas encore initialisé
     */
    public function generateHistory(int $numberOfLines, int $numberOfCells): void{
        $history = new DOMDocument(); 
        for ($i = 0; $i < $numberOfLines; $i++){ // Parcours de la liste <tr></tr>, $numberOfLines correspond au nombre de tentatives maximum
            $tr = $history->createElement("tr");
            
            $tr->setAttribute("id", $i+1); // Ajout d'un id pour chaque ligne
            for ($j = 0; $j < $numberOfCells+1; $j++){ // Parcours de la liste <td></td>, $numberOfCells+1 car on a $numberOfCells chiffres à afficher + les 2 pions
                $td = $history->createElement("td");
                $tr->appendChild($td);
            }
            for ($j = 0; $j < 2; $j++){ // Parcours de la liste <span></span>, 2 car on a 2 types de pions à afficher
                $span = $history->createElement("span");
                $tr->getElementsByTagName("td")[$numberOfCells]->appendChild($span); // Ajout de la balise <span> dans la dernière colonne 
                if ($j == 0){ // $j == 0 car on veut que le premier <spans> soit pour les pions blancs
                    $span->setAttribute("class", "pion blanc");
                } else {
                    $span->setAttribute("class", "pion rouge");
                }
            }
            $history->appendChild($tr);
        }
        $_SESSION['history'] = $history->saveHTML();
    }

    /**
     * @param DOMDocument $history
     * @param Form $form
     * @param array $emplacementTab
     * 
     * @return void
     * 
     * Met à jour l'historique du jeu
     */
    public function updateHistory(DOMDocument $history, Form $form, array $emplacementTab): void{
        $masterMind = $_SESSION['masterMind'];
        $proposition = ""; //Variable contenant la proposition du joueur
        $history->loadHTML($_SESSION['history']);

        for ($i = 0; $i < $masterMind->getSize(); $i++){ // Parcours de la liste <td></td>, on a $masterMind->getSize() chiffres à deviner
            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->setAttribute("class", "number"); // Ajout de la classe number pour le css
            $history->getElementById($masterMind->getTry())->getElementsByTagName("td")[$i]->appendChild($history->createTextNode($emplacementTab[$i])); // Ajout du chiffre dans le tableau
            $proposition = $proposition . $form->getValue("emplacement" . $i+1); // Ajout du chiffre dans la proposition
        }

        for ($i = 0; $i < 2; $i++){ // Parcours de la liste <span></span>, $i < 2 car on a 2 types de pions à afficher
            for ($j = 0; $j < $masterMind->checkCode($proposition)[$i]; $j++){
                    $history->getElementById($masterMind->getTry())->getElementsByTagName("span")[$i]->appendChild($history->createTextNode("•")); // Ajout du pion dans le tableau
                }
        }
        $_SESSION['proposition'] = $proposition; // Enregistrement de la proposition dans la session
        $_SESSION['history'] = $history->saveHTML(); // Enregistrement de l'historique dans la session
    }

    /**
     * @param int $numberOfCells
     * 
     * @return string
     * 
     * Retourne le formulaire de sélection des chiffres
     */
    public function select(int $numberOfCells): string{
        $selectMenu = new DOMDocument();
        $td = $selectMenu->createElement("td");
        for($i = 0; $i < $numberOfCells; $i++){ // Parcours de la liste <select></select>, $i < $numberOfCells car on a $numberOfCells chiffres à deviner
            $select = $selectMenu->createElement("select");
            for($j = 1; $j <= $numberOfCells+2; $j++){ // Parcours de la liste <option></option>, $j <= $numberOfCells+2 car il y a deux chiffres qui ne sont pas présent dans le code secret
                $option = $selectMenu->createElement("option");
                $option->setAttribute("value", $j); // Ajout de la valeur dans la balise <option>
                $option->appendChild($selectMenu->createTextNode($j));
                $select->appendChild($option);
            }
            $select->setAttribute("name", "emplacement" . ($i+1)); // Ajout de l'attribut name dans la balise <select> pour récupérer la valeur plus facilement
            $td->appendChild($select);
        }
        $selectMenu->appendChild($td);
        return $selectMenu->saveHTML();
    }

    /**
     * @return string
     * 
     * Retourne le bouton de relance du jeu
     */
    public function replay(): string{
        return "<td><input type='submit' value='Rejouer' name='Rejouer'></td>";
    }

    /**
     * @return string
     * 
     * Retourne le bouton de validation
     */
    public function submit(): string{
        return "<td><input type='submit' value='Valider' name='Submit'></td>";
    }

    /**
     * @param string $index
     * 
     * @return string|null
     * 
     * Retourne la valeur du formulaire
     */
    public function getValue(string $index): ?string{
        return isset($this->data[$index]) ? $this->data[$index] : null; // Vérifie si la valeur existe dans le tableau, si oui retourne la valeur, sinon retourne null
    }

}
?>