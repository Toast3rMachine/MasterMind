<?php

//Création de la class Form
class Form{

    private $data;

    public function __construct($data = array()){
        $this->data = $data;
    }

    public function select(){
        for($i = 0; $i < 4; $i++){
            echo "<td>";
            echo "<select name= place" . $i+1 . ">";
            for($j = 1; $j <= 6; $j++){
                echo "<option value=".$j.">".$j."</option>";
            }
            echo "</select>";
            echo "</td>";
        }
    }

    public function submit(){
        echo "<td><input type='submit' value='Valider'></td>";
    }

    public function getValue($index){
        return isset($this->data[$index]) ? $this->data[$index] : "Aucune valeur pour ce champ";
    }
    
}

?>