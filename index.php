<?php

$alert='';
session_start();
if (!empty($_SESSION['active'])) {
  header('location: sistema/');
}else{
    if (!empty($_POST))
    {

        if (empty($_POST['usuario'])|| empty($_POST['clave']))
        {
          $alert = 'Ingrese su usuario y su clave';
        }else {
          require_once "conexion.php";
          $usario = mysqli_real_escape_string($conection,$_POST['usuario']);
          $pass =  md5(mysqli_real_escape_string($conection,$_POST['clave']));

          $query = mysqli_query($conection,"SELECT * FROM user where user_account = '$usario' AND user_pass = '$pass'");
          mysqli_close($conection);
          $result = mysqli_num_rows($query);
          if ($result > 0) {
            $data = mysqli_fetch_array($query);


            //cualquier session que quieras usar de esta parte se extraen los datos
            $_SESSION['active'] = true;
            $_SESSION['idUser'] = $data['user_id'];
            $_SESSION['nombre'] = $data['user_acount'];

            $_SESSION['user'] = $data['usuario_empleado'];
            $_SESSION['rol'] = $data['user_type'];

            header('location: sistema/');

          }else {
              $alert = 'El usuario o la clave no son correctos';
              session_destroy();
          }
        }
    }
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  <title>Logueo | Facturación</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <section id="container">

    <form action="" method="POST">

      <h3>Iniciar Sesión</h3>
      <img src="img/login.png" alt="Login">
      <input type="text" name="usuario" placeholder="Usuario">
      <input type="password" name="clave" placeholder="Contraseña">
      <div class="alert"><?php echo isset($alert)? $alert : '' ; ?></div>
      <input type="submit" name="ingresar" value="INGRESAR">


    </form>

  </section>
</body>


</html>
