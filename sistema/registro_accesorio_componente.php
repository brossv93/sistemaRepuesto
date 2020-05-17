<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}

include_once "../conexion.php";


@$sql2 = "SELECT * from componente" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['accesorio_componente_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso algun dato obligatorio</p>';
	}else
	{
			$modelo   = $_POST['accesorio_componente_descripcion'];
			$marca   = $_POST['componente_id'];
			$precio   = $_POST['precio'];


			$query = mysqli_query($conection,"SELECT * from accesorio_componente a,componente c
                                        WHERE (a.accesorio_componente_descripcion = '$modelo' and a.componente_id = '$marca')and a.componente_id = c.componente_id ");

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0) {
				$alert = '<p class="msg_error">El CI ya existe</p>';
			}else {
        $query_insert = mysqli_query($conection,"INSERT INTO accesorio_componente(accesorio_componente_descripcion,accesorio_componente_precio,componente_id)
																								VALUES('$modelo','$precio','$marca')");
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
	<title>Registro de accesorio para el componente</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar componente</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">

					<label for="accesorio_componente_descripcion">Accesorio</label>
					<input type="text" name="accesorio_componente_descripcion" id="accesorio_componente_descripcion" placeholder="Inserte un accesorio">

					<label for="precio">Precio</label>
					<input type="text" name="precio" id="precio" placeholder="Inserte precio de compra">


					<label for="componente_id">Componente:</label>
					<select id="componente_id" name="componente_id">
						<?php
							while ($componente_id = mysqli_fetch_array($result2)) {
								echo '<option value=' . $componente_id[componente_id]. '>' . $componente_id[componente_descripcion] . '</option>';
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
