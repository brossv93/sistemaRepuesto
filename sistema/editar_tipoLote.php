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
	if (empty($_POST['tipoLote_descripcion']))
	{
			$alert= '<p class="msg_error">No algun valor obligatorio </p>';
	}else
	{
      $tipoLote_id 		   = $_POST['id'];
			$tipoLote = $_POST['tipoLote_descripcion'];



			$query = mysqli_query($conection,"SELECT * from tipoLote
																				where (tipoLote_descripcion = '$tipoLote')");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">tipoLote duplicado</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE tipoLote
																									SET tipoLote_descripcion = '$tipoLote'
																									where tipoLote_id = $tipoLote_id");



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

  header('location: lista_tipoLote.php');

}

$tipoLoteId = $_REQUEST['id'];


$sql = mysqli_query($conection,"SELECT *
															  from tipoLote
																WHERE tipoLote_id = $tipoLoteId");

  mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_tipoLote.php');
		  mysqli_close($conection);

}else {


  while ($data = mysqli_fetch_array($sql)) {
			$tipoLote_id       = $data[0];
			$tipoLote          = $data[1];
}

}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Acualizar tipoLote</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar tipoLote</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $tipoLote_id; ?>">

					<label for="nombre_tipoLote">Nombre</label>
					<input type="text" name="tipoLote_descripcion" id="tipoLote_descripcion" placeholder="Insertar un tipoLote" value="<?php echo $tipoLote; ?>">

					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Registro</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
