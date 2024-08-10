<?php

/**
 * Class Options
 * Permet de gérer les options du jeu.
 */
class Options{

    /**
     * @var int
     */
    private int $size = 4;

    /**
     * @var int
     */
    private int $maxTries = 10;


    /**
     * @return string
     * 
     * Retourne les paramètres actuels du jeu
     */
    public function currentSettings(): string{
        return "<p>Paramètres actuel : </p>
                <p>
                    Nombre de chiffres à deviner : " . $this->getSize() . "<br>
                    Nombre d'essais maximum : " . $this->getMaxTries() . "
                </p>";
    }

    /**
     * @return string
     * 
     * Retourne le formulaire pour changer la taille du code à deviner
     */
    public function changeMaxSize(): string{
        $select = "<div class='setting'>";
        $select .= "<p class='information'>Nombre de chiffres à deviner : </p>";
        $select .= "<select name='size'>";
        for ($i = 3; $i <= 7; $i++){
            if ($i == $this->getSize()) {
                $select .= "<option value='" . $i . "' selected>" . $i . "</option>";
            } else {
                $select .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }
        $select .= "</select>";
        $select .= "</div>";
        return $select;
    }

    /**
     * @return string
     * 
     * Retourne le formulaire pour changer le nombre d'essais maximum
     */
    public function changeMaxTries(): string{
        $select = "<div class='setting'>";
        $select .= "<p class='information'>Nombre d'essais maximum : </p>";
        $select .= "<select name='maxTries'>";
        for ($i = 5; $i <= 20; $i++){
            if ($i == $this->getMaxTries()){
                $select .= "<option value='" . $i . "' selected>" . $i . "</option>";
            } else {
                $select .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }
        $select .= "</select>";
        $select .= "</div>";
        return $select;
    }

    /**
     * @return string
     * 
     * Retourne le bouton pour valider les paramètres
     */
    public function submit(): string{
        return "<td><input type='submit' value='Appliquer' name='Submit'></td>";
    }


    /**
     * @param int $size
     * @param int $maxTries
     * 
     * @return void
     * 
     * Applique les paramètres passés en paramètre
     */
    public function applySettings(int $size, int $maxTries): void{
        $this->setSize($size);
        $this->setMaxTries($maxTries);
    }

    /**
     * @param int $size
     * 
     * @return void
     * 
     * Définit la taille du code à deviner
     */
    private function setSize(int $size): void{
        $this->size = $size;
    }

    /**
     * @param int $maxTries
     * 
     * @return void
     * 
     * Définit le nombre d'essais maximum
     */
    private function setMaxTries(int $maxTries): void{
        $this->maxTries = $maxTries;
    }

    /**
     * @return int
     * 
     * Retourne la taille du code à deviner
     */
    public function getSize(): int{
        return $this->size;
    }

    /**
     * @return int
     * 
     * Retourne le nombre d'essais maximum
     */
    public function getMaxTries(): int{
        return $this->maxTries;
    }

}

?>