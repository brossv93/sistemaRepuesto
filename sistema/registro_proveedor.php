<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}

include_once "../conexion.php";




//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['nombre_proveedor']))
	{
			$alert= '<p class="msg_error">No ingreso algun dato obligatorio</p>';
	}else
	{
			$nombre_proveedor   = $_POST['nombre_proveedor'];
			$contacto_proveedor = $_POST['contacto_proveedor'];
		  $telefono_proveedor = $_POST['telefono_proveedor'];
			$direccion_proveedor   = $_POST['direccion_proveedor'];

			$query = mysqli_query($conection,"SELECT * from proveedor
                                        WHERE nombre_proveedor = '$nombre_proveedor'");
      echo $nombre_proveedor.' '.$contacto_proveedor.' '.$telefono_proveedor.' '.$direccion_proveedor;
			$result3= mysqli_fetch_array($query);

			if ($result3 > 0) {
				$alert = '<p class="msg_error">El proveedor ya existe</p>';
			}else {
        $query_insert = mysqli_query($conection,"INSERT INTO proveedor(nombre_proveedor,contacto_proveedor,telefono_proveedor,direccion_proveedor)
																								VALUES('$nombre_proveedor','$contacto_proveedor','$telefono_proveedor','$direccion_proveedor')");
				if ($query_insert) {
						$alert = '<p class="msg_save">Insertado Correctamente</p>';
				}else {
						$alert = '<p class="msg_error">Error al crear el registro</p>';
				}
			}

	}
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Registro de Proveedor</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar Proveedor</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">

					<label for="nombre_proveedor">Nombre del Proveedor</label>
					<input type="text" name="nombre_proveedor" id="nombre_proveedor" placeholder="Inserte un Nombre">
          <label for="contacto_proveedor">Conctacto</label>
          <input type="text" name="contacto_proveedor" id="contacto_proveedor" placeholder="Inserte un Nombre de contacto">
          <label for="telefono_proveedor">Telefono</label>
          <input type="text" name="telefono_proveedor" id="telefono_proveedor" placeholder="Inserte un Nro de Telefono">
          <label for="direccion_proveedor">Direccion</label>
          <input type="text" name="direccion_proveedor" id="direccion_proveedor" placeholder="Inserte una Direccion">

					<button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
