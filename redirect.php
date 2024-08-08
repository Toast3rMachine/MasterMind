<?php
if(isset($_POST['Submit'])){
    $emplacement1 = $_POST['emplacement1'];
    $emplacement2 = $_POST['emplacement2'];
    $emplacement3 = $_POST['emplacement3'];
    $emplacement4 = $_POST['emplacement4'];
// if($name !=''&& $email !=''&& $contact !=''&& $address !=''){
    header("Location: index.php");
    // }
}
?>