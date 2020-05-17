<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}

include_once "../conexion.php";


@$sql2 = "SELECT * from tipoLote" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['lote_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso algun dato obligatorio</p>';
	}else
	{
			$lote   = $_POST['lote_descripcion'];
			$lote_numero   = $_POST['lote_numero'];
			$tipoLote   = $_POST['tipoLote_id'];

			$query = mysqli_query($conection,"SELECT * from lote m,tipoLote ma
                                        WHERE (lote_descripcion = '$lote')and m.tipoLote_id = ma.tipoLote_id ");

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0) {
				$alert = '<p class="msg_error">El CI ya existe</p>';
			}else {
        $query_insert = mysqli_query($conection,"INSERT INTO lote(lote_descripcion,lote_numero,tipoLote_id)
																								VALUES('$lote','$lote_numero','$tipoLote')");
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
	<title>Registro de lote</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar lote</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">

					<label for="nombre_lote">lote</label>
					<input type="text" name="lote_descripcion" id="lote_descripcion" placeholder="Inserte un lote">

					<label for="numero_lote">lote</label>
					<input type="text" name="lote_numero" id="lote_numero" placeholder="Inserte un numero">


					<label for="tipoLote_id">Seleccione la tipoLote:</label>
					<select id="tipoLote_id" name="tipoLote_id">
						<?php
							while ($tipoLote_id = mysqli_fetch_array($result2)) {
								echo '<option value=' . $tipoLote_id[tipoLote_id]. '>' . $tipoLote_id[tipoLote_descripcion] . '</option>';
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
