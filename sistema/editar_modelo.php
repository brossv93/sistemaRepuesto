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

@$sql = "SELECT * from marca" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);


//en esta parte del codigo recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['modelo_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso Usuario </p>';
	}else
	{
			$cod_modelo 			 = $_POST['id'];
			$marca_id 		 = $_POST['marca_id'];
			$modelo = $_POST['modelo_descripcion'];





			$query = mysqli_query($conection,"SELECT * from modelo
																				where (modelo_descripcion = '$modelo' ) and modelo_id != '$cod_modelo'");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">Persona o Usuario ya existente</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE modelo
																									SET modelo_descripcion = '$modelo',marca_id = '$marca_id'
																									where modelo_id = $cod_modelo");
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



$sql = mysqli_query($conection,"SELECT m.modelo_id,m.modelo_descripcion,ma.marca_id,ma.marca_descripcion
															  from modelo m
																INNER JOIN marca ma ON m.marca_id = ma.marca_id
																WHERE m.modelo_id = $codModelo");




$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_modelo.php');
		  mysqli_close($conection);

}else {
	$option = '';


  while ($data = mysqli_fetch_array($sql)) {
			$cod_modelo1   = $data['modelo_id'];
			$modelo          = $data['modelo_descripcion'];
			$marca_id        = $data['marca_id'];
			$marca   = $data['marca_descripcion'];

			if ($marca_id == 1) {
				  $option = '<option value="'.$marca_id.'" select> '.$marca.'</option>';
			}else {
				  $option = '<option value="'.$marca_id.'"select> '.$marca.'</option>';
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
        <h1><i class="fas fa-edit"></i> Actualizar Modelo</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $cod_modelo1; ?>">

					<input type="hidden" name="marca_id" value=<?php echo $marca_id; ?>>

					<label for="modelo_descripcion">Modelo</label>
					<input type="text" name="modelo_descripcion" id="modelo_descripcion" placeholder="modelo" value="<?php echo $modelo; ?>">
					</select>
					<label for="marca_id">Seleccione el tipo de usuario:</label>
          <select id="marca_id"  class="notItemOne" name="marca_id" value= <?php echo $marca_id; ?>>
            <?php
 							echo $option;
              while ($marca_id = mysqli_fetch_array($result2)) {
                echo '<option value=' . $marca_id[marca_id]. '>' . $marca_id[marca_descripcion] . '</option>';
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
