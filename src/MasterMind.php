<?php

//Création de la class MasterMind
class MasterMind{
    
    private $size = 4;
    private $try = 0;
    private $maxTries = 10;
    private $secretCode = array(); //Tableau contenant le code à deviner
    private $win = false;
    private $lose = false;

    //Fonction générant le code à deviner
    public function generateSecretCode(){
        $randomNumber = 0;
        for($i = 0; $i < $this->size; $i++){ //Boucle pour générer les 4 chiffres du code à partir de la taille attribué à la classe
            $randomNumber = random_int(1, 6); //Génère un nombre aléatoire entre 1 et 6
            if (in_array($randomNumber, $this->secretCode)){ //Vérifie si le nombre généré est déjà dans le tableau
                $i--;
                continue;
            } else {
                array_push($this->secretCode, $randomNumber);
            }
        }
        return $this->secretCode;
    }

    //Fonction vérifiant si le code entré par l'utilisateur est correct
    public function checkCode($code){
        $code = str_split($code); //Transforme le code entré en tableau
        $correct = 0;
        $misplaced = 0;
        for($i = 0; $i < $this->size; $i++){ //Boucle pour vérifier chaque chiffre du code
            if (in_array($code[$i], $this->secretCode)){ //Vérifie si le chiffre est dans le code
                $misplaced++;
                if ($code[$i] == $this->secretCode[$i]){ //Vérifie si le chiffre est à la bonne place
                    $misplaced--;
                    $correct++;
                }
            }
        }
        if ($correct == $this->size){ //Vérifie si le code est correct
            $this->win = true;
        }
        return array($correct, $misplaced);
    }

    //Fonction incrémentant le nombre d'essaies
    public function incrementTry(){
        $this->try++;
        if ($this->try >= $this->maxTries){
            $this->lose = true;
        }
    }

    //Fonction retournant le nombre d'essaies
    public function getTry(){
        return $this->try;
    }

    //Fonction retournant le nombre d'essaies maximum
    public function getMaxTries(){
        return $this->maxTries;
    }

    //Fonction retournant le code à deviner
    public function getSecretCode(){
        $code = "";
        foreach($this->secretCode as $i){
            $code = $code . $i;
        }
        return $code;
    }

    //Fonction retournant si le joueur a gagné
    public function getWin(){
        return $this->win;
    }

    //Fonction retournant si le joueur a perdu
    public function getLose(){
        return $this->lose;
    }

}

?>