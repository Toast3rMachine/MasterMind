<?php

require 'MasterMind.php';
require 'Form.php';

$masterMind = new MasterMind();
// var_dump($masterMind);

// var_dump($masterMind->check_code(1234));

$form = new Form($_POST);


// créer méthode intermédiaire dans MM
// implémenter POST / GET pour récupérer données formulaire

?>

<form method="post" action="">
    <table class='table'>
    <tr>
        <th colspan="5">A vous de jouer !</th>
    </tr>
    <tr>
        <?php 
        
        var_dump($masterMind->generate_secret_code()); 
        $code = "";
        echo $form->select(); 
        echo $form->submit();
        for ($i = 0; $i < 4; $i++){
            $code = $code . $form->getValue("place" . $i+1);
        }
        var_dump($code);
        var_dump($masterMind->check_code($code));
        
        ?>
    </tr>
    </table>
</form>