<?php

//Création de la class MasterMind
class MasterMind{
    
    private $size = 4;
    private $max_tries = 10;
    private $secret_code = array(); //Tableau contenant le code à deviner
    private $secret_code_generate = false;
    private $colors = array("White", "Red");

    //Fonction générant le code à deviner
    public function generate_secret_code(){
        if ($this->secret_code_generate){ //Vérifie si le code a déjà été généré
            return $this->secret_code;
        } else {
            $random_number = 0;
            for($i = 0; $i < $this->size; $i++){ //Boucle pour générer les 4 chiffres du code à partir de la taille attribué à la classe
                $random_number = random_int(1, 6); //Génère un nombre aléatoire entre 1 et 6
                if (in_array($random_number, $this->secret_code)){ //Vérifie si le nombre généré est déjà dans le tableau
                    $i--;
                    continue;
                } else {
                    array_push($this->secret_code, $random_number);
                }
            }
            $this->secret_code_generate = true;
        }
        return $this->secret_code;
    }

    //Fonction vérifiant si le code entré par l'utilisateur est correct
    public function check_code($code){
        $code = str_split($code); //Transforme le code entré en tableau
        $correct = 0;
        $misplaced = 0;
        for($i = 0; $i < $this->size; $i++){ //Boucle pour vérifier chaque chiffre du code
            if (in_array($code[$i], $this->secret_code)){ //Vérifie si le chiffre est dans le code
                $misplaced++;
                if ($code[$i] == $this->secret_code[$i]){ //Vérifie si le chiffre est à la bonne place
                    $misplaced--;
                    $correct++;
                }
            }
        }
        return array($correct, $misplaced);
    }

}

?>