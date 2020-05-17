<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}

include_once "../conexion.php";



@$sql = "SELECT * from persona" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);

@$sql2 = "SELECT cod_tipo_empleado,descripcion_empleado from tipo_empleado" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);
//mysqli_close($conection);
//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['usuario']) || empty($_POST['clave']))
	{
			$alert= '<p class="msg_error">No ingreso Usuario o Contraseña</p>';
	}else
	{
			$persona= $_POST['id'];
			$empleado=$_POST['tipo_empleado'];
			$usuario=$_POST['usuario'];
			$clave= md5($_POST['clave']);



			$query = mysqli_query($conection,"SELECT * from empleado e,persona p,tipo_empleado t
																				where (e.usuario_empleado = '$usuario' and t.cod_tipo_empleado = '$empleado') and p.cod_persona = e.persona_cod_persona
																				and t.cod_tipo_empleado = e.tipo_empleado_cod_tipo_empleado");
			//mysqli_close($conection);

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0) {
				$alert = '<p class="msg_error">Persona o Usuario ya existente</p>';
			}else {
				$query_insert = mysqli_query($conection,"INSERT INTO empleado(persona_cod_persona,tipo_empleado_cod_tipo_empleado,usuario_empleado,pass_empleado)
																								 VALUES('$persona','$empleado','$usuario','$clave')");
				if ($query_insert) {
						$alert = '<p class="msg_save">Insertado Correctamente</p>';
				}else {
						$alert = '<p class="msg_error">Error al crear el usuario</p>';
				}
			}

	}
}

//todo lo de acá abajo es para llevar al form y es prueba si no fuciona elimanar todo lo de abajo
$auxBusquedaNombre ="Seleccione una persona de la lista";
$auxBusquedaApellido = "";

	if (empty($_REQUEST['id'])) {

	  $_REQUEST['id']=0;

	}
$codPersona = $_REQUEST['id'];



$sql = mysqli_query($conection,"SELECT *
															  from persona
																WHERE cod_persona = $codPersona");


$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
	//header('location: lista_personaBuscar.php');
	$cod_persona = 0;

		//  mysqli_close($conection);

}else {


  while ($data = mysqli_fetch_array($sql)) {
			$cod_persona   = $data['cod_persona'];
			$nombre          = $data['nombre_persona'];
			$apellido        = $data['apellido_persona'];

			$auxBusquedaNombre = $nombre;
			$auxBusquedaApellido = $apellido;
  }
}




//

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Registro de usuario</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar Usuario</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">

					<input type="hidden" name="id" value="<?php echo $cod_persona; ?>">

          <label for="persona">Seleccione al Usuario:</label>
          <input type="text" name="persona" id="persona" placeholder="Seleccione una persona de la lista " value="<?php echo $auxBusquedaNombre .' '. $auxBusquedaApellido; ?>">
					<br>
					<a href="lista_personaBuscar.php" class="btn_search"><i class="fas fa-search"></i> Buscar una persona</a>


					<label for="tipo_empleado">Seleccione el tipo de usuario:</label>
          <select id="tipo_empleado" name="tipo_empleado">
            <?php
              while ($tipo_empleado = mysqli_fetch_array($result2)) {
                echo '<option value=' . $tipo_empleado[cod_tipo_empleado]. '>' . $tipo_empleado[descripcion_empleado] . '</option>';
              }
             ?>
          </select>

					<label for="usuario">Usuario</label>
					<input type="text" name="usuario" id="usuario" placeholder="Usuario">
					<label for="clave">Clave</label>
					<input type="password" name="clave" id="clave" placeholder="Ingrese clave de acceso">

					 <button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Usuario</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
