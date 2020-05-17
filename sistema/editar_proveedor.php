<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include_once "../conexion.php";

if (empty($_SESSION['active'])) {
	header('location: ../');
}



//en esta parte del codigo recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['nombre_proveedor']))
	{
			$alert= '<p class="msg_error">No algun valor obligatorio </p>';
	}else
	{
      $cod_proveedor		   = $_POST['id'];
			$nombre_proveedor= $_POST['nombre_proveedor'];
			$contacto_proveedor= $_POST['contacto_proveedor'];
			$telefono_proveedor = $_POST['telefono_proveedor'];
		  $direccion_proveedor = $_POST['direccion_proveedor'];



			$query = mysqli_query($conection,"SELECT * from proveedor
																				where (nombre_proveedor = '$nombre_proveedor' and cod_proveedor != '$cod_proveedor')");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">proveedor duplicado</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE proveedor
																									SET nombre_proveedor = '$nombre_proveedor',contacto_proveedor = '$contacto_proveedor',telefono_proveedor = '$telefono_proveedor',direccion_proveedor = '$direccion_proveedor'
																									where cod_proveedor = $cod_proveedor");



				if ($sql_update)
				{
						$alert = '<p class="msg_save">Registro actulizado correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al actualizar el Registro</p>';
				}
			}

	}

}
//fin de la parte del codigo donde recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos


//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
//correcta el form

if (empty($_REQUEST['id'])) {

  header('location: lista_proveedores.php');

}

$codProveedor = $_REQUEST['id'];



$sql = mysqli_query($conection,"SELECT *
															  from proveedor
																WHERE cod_proveedor = $codProveedor");

  mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_proveedores.php');
		  mysqli_close($conection);

}else {


  while ($data = mysqli_fetch_array($sql)) {
			$cod_proveedor        = $data[0];
			$nombre_proveedor     = $data[1];
			$contacto_proveedor   = $data[2];
			$telefono_proveedor   = $data[3];
			$direccion_proveedor  = $data[4];


  }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Acualizar Proveedor</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar Proveedor</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $cod_proveedor; ?>">

					<label for="nombre_proveedor">Nombre</label>
					<input type="text" name="nombre_proveedor" id="nombre_proveedor" placeholder="Insertar nombre" value="<?php echo $nombre_proveedor; ?>">
          <label for="contacto_proveedor">Contacto</label>
          <input type="text" name="contacto_proveedor" id="contacto_proveedor" placeholder="Insertar contacto" value="<?php echo $contacto_proveedor; ?>">
          <label for="telefono_proveedor">Telefono</label>
          <input type="text" name="telefono_proveedor" id="telefono_proveedor" placeholder="Insertar telefono" value="<?php echo $telefono_proveedor; ?>">
          <label for="direccion_proveedor">Direccion</label>
          <input type="text" name="direccion_proveedor" id="direccion_proveedor" placeholder="Insertar direccion" value="<?php echo $direccion_proveedor; ?>">

					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
