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
	if (empty($_POST['componente_descripcion']))
	{
			$alert= '<p class="msg_error">No algun valor obligatorio </p>';
	}else
	{
      $componente_id 		   = $_POST['id'];
			$componente = $_POST['componente_descripcion'];



			$query = mysqli_query($conection,"SELECT * from componente
																				where (componente_descripcion = '$componente')");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">Componente duplicado</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE componente
																									SET componente_descripcion = '$componente'
																									where componente_id = $componente_id");



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

  header('location: lista_componente.php');

}

$componenteId = $_REQUEST['id'];


$sql = mysqli_query($conection,"SELECT *
															  from componente
																WHERE componente_id = $componenteId");

  mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_componente.php');
		  mysqli_close($conection);

}else {


  while ($data = mysqli_fetch_array($sql)) {
			$componente_id       = $data[0];
			$componente          = $data[1];
}

}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Acualizar Persona</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar Persona</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $componente_id; ?>">

					<label for="nombre_componente">Nombre</label>
					<input type="text" name="componente_descripcion" id="componente_descripcion" placeholder="Insertar un componente" value="<?php echo $componente; ?>">

					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
