<?php

require_once('Options.php');

/**
 * Class MasterMind
 * Permet de créer un masterMind.
 */
class MasterMind{

    /**
     * @var int
     */
    private int $try = 0;

    /**
     * @var array
     */
    private array $secretCode = array(); //Tableau contenant le code à deviner

    /**
     * @var bool
     */
    private bool $win = false;

    /**
     * @var bool
     */
    private bool $lose = false;

    /**
     * @var Options
     */
    protected Options $options;


    /**
     * Constructeur de la classe
     */
    public function __construct(){
        if (!isset($_SESSION['options'])){ // Si la session n'existe pas, on la crée
            $this->options = new Options();
            $_SESSION['options'] = $this->options;
        } else {
            $this->options = $_SESSION['options'];
        }
    }

    /**
     * @return array
     * 
     * Génère un code aléatoire de $this->size chiffres entre 1 et 6
     */
    public function generateSecretCode(): array{
        $randomNumber = 0;
        for($i = 0; $i < $this->options->getSize(); $i++){ //Boucle pour générer le code à partir de la taille du code secret attribué à la classe Options
            $randomNumber = random_int(1, $this->options->getSize()+2); //Génère un nombre aléatoire entre 1 et la taille du code secret + 2
            if (in_array($randomNumber, $this->secretCode)){ //Vérifie si le nombre généré est déjà dans le tableau
                $i--;
                continue;
            } else {
                array_push($this->secretCode, $randomNumber);
            }
        }
        return $this->secretCode;
    }

    
    /**
     * @param string $code
     * 
     * @return array
     * 
     * Vérifie si le code entré est correct et retourne le nombre de chiffres bien placés et mal placés
     */
    public function checkCode(string $code): array{
        $code = str_split($code); //Transforme le code entré en tableau
        $correct = 0;
        $misplaced = 0;
        for($i = 0; $i < $this->options->getSize(); $i++){ //Boucle pour vérifier chaque chiffre du code
            if (in_array($code[$i], $this->secretCode)){ //Vérifie si le chiffre est dans le code
                $misplaced++;
                if ($code[$i] == $this->secretCode[$i]){ //Vérifie si le chiffre est à la bonne place
                    $misplaced--;
                    $correct++;
                }
            }
        }
        if ($correct == $this->options->getSize()){ //Vérifie si le code est correct
            $this->win = true;
        }
        return array($correct, $misplaced);
    }

    /**
     * @return void
     * 
     * Incrémente le nombre d'essaies et vérifie si le joueur a utilisé tous ses essaies
     */
    public function incrementTry(): void{
        $this->try++;
        if ($this->try >= $this->options->getMaxTries()){ //Si le nombre d'essaies est supérieur ou égal au nombre d'essaies maximum alors le joueur a 
            $this->lose = true;
        }
    }

    /**
     * @return int
     * 
     * Retourne le nombre d'essaies
     */
    public function getTry(): int{
        return $this->try;
    }

    /**
     * @return int
     * 
     * Retourne la taille du code à deviner
     */
    public function getSize(): int{
        return $this->options->getSize();
    }
    
    /**
     * @return int
     * 
     * Retourne le nombre d'essaies maximum
     */
    public function getMaxTries(): int{
        return $this->options->getMaxTries();
    }

    /**
     * @return string
     * 
     * Retourne le code secret
     */
    public function getSecretCode(): string{
        $code = "";
        foreach($this->secretCode as $i){
            $code = $code . $i;
        }
        return $code;
    }

    /**
     * @return bool
     * 
     * Retourne si le joueur a gagné
     */
    public function getWin(): bool{
        return $this->win;
    }

    /**
     * @return bool
     * 
     * Retourne si le joueur a perdu
     */
    public function getLose(): bool{
        return $this->lose;
    }

}

?>