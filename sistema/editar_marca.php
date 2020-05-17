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
	if (empty($_POST['marca_descripcion']))
	{
			$alert= '<p class="msg_error">No algun valor obligatorio </p>';
	}else
	{
      $marca_id 		   = $_POST['id'];
			$marca = $_POST['marca_descripcion'];



			$query = mysqli_query($conection,"SELECT * from marca
																				where (marca_descripcion = '$marca')");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">marca duplicado</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE marca
																									SET marca_descripcion = '$marca'
																									where marca_id = $marca_id");



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

  header('location: lista_marca.php');

}

$marcaId = $_REQUEST['id'];


$sql = mysqli_query($conection,"SELECT *
															  from marca
																WHERE marca_id = $marcaId");

  mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_marca.php');
		  mysqli_close($conection);

}else {


  while ($data = mysqli_fetch_array($sql)) {
			$marca_id       = $data[0];
			$marca          = $data[1];
}

}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Acualizar marca</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar marca</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $marca_id; ?>">

					<label for="nombre_marca">Nombre</label>
					<input type="text" name="marca_descripcion" id="marca_descripcion" placeholder="Insertar un marca" value="<?php echo $marca; ?>">

					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
