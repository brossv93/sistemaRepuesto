<?php

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'db_repuesto';

    $conection = @mysqli_connect($host,$user,$pass,$db);



    if (!$conection) {
       echo "Error en la conección";
    }


 ?>
