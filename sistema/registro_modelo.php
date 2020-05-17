<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}

include_once "../conexion.php";


@$sql2 = "SELECT * from marca" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['modelo_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso algun dato obligatorio</p>';
	}else
	{
			$modelo   = $_POST['modelo_descripcion'];
			$marca   = $_POST['marca_id'];

			$query = mysqli_query($conection,"SELECT * from modelo m,marca ma
                                        WHERE (modelo_descripcion = '$modelo')and m.marca_id = ma.marca_id ");

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0) {
				$alert = '<p class="msg_error">El CI ya existe</p>';
			}else {
        $query_insert = mysqli_query($conection,"INSERT INTO modelo(modelo_descripcion,marca_id)
																								VALUES('$modelo','$marca')");
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
	<title>Registro de modelo</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar modelo</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">

					<label for="nombre_modelo">modelo</label>
					<input type="text" name="modelo_descripcion" id="modelo_descripcion" placeholder="Inserte un modelo">

					<label for="marca_id">Seleccione la marca:</label>
					<select id="marca_id" name="marca_id">
						<?php
							while ($marca_id = mysqli_fetch_array($result2)) {
								echo '<option value=' . $marca_id[marca_id]. '>' . $marca_id[marca_descripcion] . '</option>';
							}
						 ?>
					</select>

					<button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
