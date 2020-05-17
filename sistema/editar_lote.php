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

@$sql = "SELECT * from tipoLote" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);


//en esta parte del codigo recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['lote_descripcion']))
	{
			$alert= '<p class="msg_error">No ingreso Usuario </p>';
	}else
	{
			$cod_lote 			 = $_POST['id'];
			$tipoLote_id 		 = $_POST['tipoLote_id'];
			$lote = $_POST['lote_descripcion'];
			$lote_numero = $_POST['lote_numero'];





			$query = mysqli_query($conection,"SELECT * from lote
																				where (lote_descripcion = '$lote' ) and lote_id != '$cod_lote'");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">Persona o Usuario ya existente</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE lote
																									SET lote_descripcion = '$lote',lote_numero ='$lote_numero',tipoLote_id = '$tipoLote_id'
																									where lote_id = $cod_lote");
				}


				if ($sql_update)
				{
						$alert = '<p class="msg_save">lote actulizado correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al actualizar el lote</p>';
				}
			}

	}


//fin de la parte del codigo donde recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos


//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
//correcta el form

if (empty($_REQUEST['id'])) {

  header('location: lista_lote.php');

}

$codlote = $_REQUEST['id'];



$sql = mysqli_query($conection,"SELECT m.lote_id,m.lote_descripcion,m.lote_numero,ma.tipoLote_id,ma.tipoLote_descripcion
															  from lote m
																INNER JOIN tipoLote ma ON m.tipoLote_id = ma.tipoLote_id
																WHERE m.lote_id = $codlote");




$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_lote.php');
		  mysqli_close($conection);

}else {
	$option = '';


  while ($data = mysqli_fetch_array($sql)) {
			$cod_lote1   = $data['lote_id'];
			$lote          = $data['lote_descripcion'];
			$tipoLote_id        = $data['tipoLote_id'];
			$lote_numero        = $data['lote_numero'];
			$tipoLote   = $data['tipoLote_descripcion'];

			if ($tipoLote_id == 1) {
				  $option = '<option value="'.$tipoLote_id.'" select> '.$tipoLote.'</option>';
			}else {
				  $option = '<option value="'.$tipoLote_id.'"select> '.$tipoLote.'</option>';
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
	<title>Actualizar lote</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar lote</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $cod_lote1; ?>">

					<input type="hidden" name="tipoLote_id" value=<?php echo $tipoLote_id; ?>>

					<label for="lote_descripcion">lote</label>
					<input type="text" name="lote_descripcion" id="lote_descripcion" placeholder="lote" value="<?php echo $lote; ?>">
					<label for="lote_numero">lote</label>
					<input type="text" name="lote_numero" id="lote_numero" placeholder="numero" value="<?php echo $lote_numero; ?>">

					</select>
					<label for="tipoLote_id">Seleccione el tipo de usuario:</label>
          <select id="tipoLote_id"  class="notItemOne" name="tipoLote_id" value= <?php echo $tipoLote_id; ?>>
            <?php
 							echo $option;
              while ($tipoLote_id = mysqli_fetch_array($result2)) {
                echo '<option value=' . $tipoLote_id[tipoLote_id]. '>' . $tipoLote_id[tipoLote_descripcion] . '</option>';
              }
             ?>
          </select>


					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar lote</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
