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
	if (empty($_POST['lado_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso algun dato obligatorio</p>';
	}else
	{
			$lado   = $_POST['lado_descripcion'];

			$query = mysqli_query($conection,"SELECT * from componente
                                        WHERE componente_descripcion = '$lado'");

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0) {
				$alert = '<p class="msg_error">El CI ya existe</p>';
			}else {
        $query_insert = mysqli_query($conection,"INSERT INTO lado(lado_descripcion)
																								VALUES('$lado')");
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
	<title>Registro de componente</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar Lado</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">

					<label for="nombre_lado">Lado</label>
					<input type="text" name="lado_descripcion" id="lado_descripcion" placeholder="Inserte un lado">

					<button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
