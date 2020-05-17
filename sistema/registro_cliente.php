<?php
session_start();


include_once "../conexion.php";



@$sql = "SELECT * from persona" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);



//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['empresa_cliente']) || empty($_POST['ruc_cliente']))
	{
			$alert= '<p class="msg_error">No ingreso algun campo obligatorio</p>';
	}else
	{
			$ruc        = $_POST['ruc_cliente'];
			$direccion  =$_POST['direccion_cliente'];
			$telefono   =$_POST['telefono_cliente'];
			$empresa	  = ($_POST['empresa_cliente']);
			$correo	  	= ($_POST['correo_cliente']);
			$persona	  =$_POST['persona'];


			$query = mysqli_query($conection,"SELECT * from cliente
																				WHERE ruc_cliente = $ruc or empresa_cliente = '$empresa'");

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0)
			 {
				$alert = '<p class="msg_error">Numero de Ruc duplicado - Cliene ya existente</p>';
			 }else
			 {
				$query_insert = mysqli_query($conection,"INSERT INTO cliente(ruc_cliente,direccion_cliente,telefono_cliente,empresa_cliente,correo_cliente,contacto_cliente)
																																			VALUES('$ruc','$direccion','$telefono','$empresa','$correo','$persona')");
				if ($query_insert)
				{
						$alert = '<p class="msg_save">Insertado Correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al crear el cliente</p>';
				}
			}
	}
//	mysqli_close($conection);
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Registro de cliente</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar Cliente</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">


					<label for="ruc_cliente">Ruc</label>
					<input type="text" name="ruc_cliente" id="ruc_cliente" placeholder="Insertar Ruc">
					<label for="direccion_cliente">Direccion</label>
					<input type="text" name="direccion_cliente" id="direccion_cliente" placeholder="Ingrese direccion de cliente">
          <label for="telefono_cliente">Telefono</label>
          <input type="text" name="telefono_cliente" id="telefono_cliente" placeholder="Ingrese numero de telefono">

          <label for="empresa_cliente">Empresa</label>
					<input type="text" name="empresa_cliente" id="empresa_cliente" placeholder="Ingrese Empresa">

					<label for="correo_cliente">Correo</label>
					<input type="email" name="correo_cliente" id="correo_cliente" placeholder="Ingrese correo">

					<label for="persona">Contacto</label>
          <textarea name="persona" rows="10" cols="45" placeholder="Inserte Contacto"></textarea>

					 <button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Cliente</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
