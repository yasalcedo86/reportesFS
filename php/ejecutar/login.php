<?php 
//url
// https://apphermes.webcindario.com/bisuteria/controlador/ejecutar/logeo.php?user=gg&pass=1234
include '../clases/Login.php';
$a = new Login();
echo $a->userLogin($_POST['user'], $_POST['pass']);
//echo $a->userRegistration('lel','1234','gg', $ramdon);

