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

@$sql = "SELECT * from componente" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);


//en esta parte del codigo recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['accesorio_componente_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso algun registro </p>';
	}else
	{
			$cod_accesorio 			 = $_POST['id'];
			$accesorio 		 = $_POST['accesorio_componente_descripcion'];
			$precio 		 = $_POST['accesorio_componente_precio'];
			$componente = $_POST['componente_id'];





			$query = mysqli_query($conection,"SELECT * from accesorio_componente
																				where (accesorio_componente_descripcion = '$accesorio' and componente_id = $componente) and accesorio_componente_id != $cod_accesorio  ");

			$result3= mysqli_fetch_array($query);


			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">Persona o Usuario ya existente</p>';
			}else
			{

				echo "entro acá";
					$sql_update = mysqli_query($conection, "UPDATE accesorio_componente
																									SET accesorio_componente_descripcion = '$accesorio',accesorio_componente_precio='$precio',componente_id = $componente
																									where accesorio_componente_id = $cod_accesorio");
				}


				if ($sql_update)
				{
						$alert = '<p class="msg_save">Modelo actulizado correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al actualizar el modelo</p>';
				}
			}

	}


//fin de la parte del codigo donde recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos


//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
//correcta el form

if (empty($_REQUEST['id'])) {

  header('location: lista_modelo.php');

}

$codModelo = $_REQUEST['id'];



$sql = mysqli_query($conection,"SELECT a.accesorio_componente_id,a.accesorio_componente_descripcion,a.accesorio_componente_precio,c.componente_id,c.componente_descripcion
															  from accesorio_componente a
																INNER JOIN componente c ON a.componente_id = c.componente_id
																WHERE a.accesorio_componente_id = $codModelo");




$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_accesorio_componente.php');
		  mysqli_close($conection);

}else {
	$option = '';


  while ($data = mysqli_fetch_array($sql)) {
			$cod_accesorio   = $data['accesorio_componente_id'];
			$accesorio          = $data['accesorio_componente_descripcion'];
			$precio        = $data['accesorio_componente_precio'];
			$componente_id   = $data['componente_id'];
			$componente_descripcion   = $data['componente_descripcion'];

			if ($componente_id == 1) {
				  $option = '<option value="'.$componente_id.'" select> '.$componente_descripcion.'</option>';
			}else {
				  $option = '<option value="'.$componente_id.'"select> '.$componente_descripcion.'</option>';
			}
			//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
			//correcta el form

  }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Actualizar modelo</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar accesorio</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $cod_accesorio; ?>">

					<input type="hidden" name="marca_id" value=<?php echo $componente_id; ?>>

					<label for="accesorio_componente_descripcion">Accesorio</label>
					<input type="text" name="accesorio_componente_descripcion" id="accesorio_componente_descripcion" placeholder="accesorio" value="<?php echo $accesorio; ?>">
					<label for="accesorio_componente_precio">Accesorio</label>
					<input type="text" name="accesorio_componente_precio" id="accesorio_componente_precio" placeholder="precio" value="<?php echo $precio; ?>">
					</select>
					<label for="componente_id">Seleccione el componente:</label>
          <select id="componente_id"  class="notItemOne" name="componente_id" value= <?php echo $componente_id; ?>>
            <?php
 							echo $option;
              while ($componente_id = mysqli_fetch_array($result)) {
                echo '<option value=' . $componente_id[componente_id]. '>' . $componente_id[componente_descripcion] . '</option>';
              }
             ?>
          </select>


					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Modelo</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
