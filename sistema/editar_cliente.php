<?php
session_start();

include_once "../conexion.php";

if (empty($_SESSION['active'])) {
	header('location: ../');
}

@$sql = "SELECT * from persona" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);


//en esta parte del codigo recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['ruc_cliente']))
	{
			$alert= '<p class="msg_error">No ingreso Ruc </p>';
	}else
	{
			$cod_cliente 		   = $_POST['id'];
			$persona					 = $_POST['persona'];
			$ruc_cliente			 =$_POST['ruc_cliente'];
			$direccion_cliente =$_POST['direccion_cliente'];
      $telefono_cliente  =$_POST['telefono_cliente'];
      $empresa_cliente   =$_POST['empresa_cliente'];
      $correo_cliente    =$_POST['correo_cliente'];


			$query = mysqli_query($conection,"SELECT * from cliente
																				where (ruc_cliente = $ruc_cliente or empresa_cliente = '$empresa_cliente') and cod_cliente != $cod_cliente");

			$result3= mysqli_fetch_array($query);


			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">Ruc o empresa ya existente</p>';
			}else
			{


					$sql_update = mysqli_query($conection, "UPDATE cliente
																									SET ruc_cliente = '$ruc_cliente',direccion_cliente = '$direccion_cliente',telefono_cliente = '$telefono_cliente',empresa_cliente = '$empresa_cliente',correo_cliente = '$correo_cliente',contacto_cliente = '$persona'
																									where cod_cliente = $cod_cliente");



				if ($sql_update)
				{
						$alert = '<p class="msg_save">Cliente actulizado correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al actualizar el cliente</p>';
				}
			}

	}

}
//fin de la parte del codigo donde recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos


//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
//correcta el form

if (empty($_REQUEST['id'])) {

  header('location: lista_cliente.php');

}

$codCliente = $_REQUEST['id'];



$sql = mysqli_query($conection,"SELECT e.cod_cliente,e.ruc_cliente,e.direccion_cliente,e.telefono_cliente,e.empresa_cliente,e.correo_cliente,e.contacto_cliente
															  from cliente e
																WHERE e.cod_cliente = $codCliente");

//  mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_cliente.php');
		  mysqli_close($conection);

}else {
	$option = '';
	$option2 = '';

  while ($data = mysqli_fetch_array($sql)) {
			$cod_cliente              = $data['cod_cliente'];
			$contacto                   = $data['contacto_cliente'];
			$ruc_cliente              = $data['ruc_cliente'];
			$direccion_cliente        = $data['direccion_cliente'];
			$empresa_cliente          = $data['empresa_cliente'];
      $telefono_cliente         = $data['telefono_cliente'];
      $correo_cliente           = $data['correo_cliente'];


			//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
			//correcta el form
			//////////////**///////////////////////////
			//acá debajo va el que trae el nombre_persona con el codigo modificado per mua el engineer Ross


  }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Acualizar Cliente</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar Cliente</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $cod_cliente; ?>">


					<label for="ruc_cliente">Ruc</label>
					<input type="text" name="ruc_cliente" id="ruc_cliente" placeholder="Ruc" value="<?php echo $ruc_cliente; ?>">
          <label for="direccion_cliente">Dirccion</label>
					<input type="text" name="direccion_cliente" id="direccion_cliente" placeholder="Direccion" value="<?php echo $direccion_cliente; ?>">
          <label for="telefono_cliente">Telefono</label>
					<input type="text" name="telefono_cliente" id="telefono_cliente" placeholder="Telefono" value="<?php echo $telefono_cliente; ?>">
          <label for="empresa_cliente">Empresa</label>
					<input type="text" name="empresa_cliente" id="empresa_cliente" placeholder="Empresa" value="<?php echo $empresa_cliente; ?>">
          <label for="correo_cliente">Correo</label>
					<input type="text" name="correo_cliente" id="correo_cliente" placeholder="Correo" value="<?php echo $correo_cliente; ?>">

					<label for="persona">Contacto</label>
          <textarea name="persona" rows="10" cols="45" placeholder="Inserte Contacto"><?php echo $contacto; ?></textarea>


					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Cliente</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
