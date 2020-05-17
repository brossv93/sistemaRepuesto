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

@$sql = "SELECT * from persona" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);

@$sql2 = "SELECT * from tipo_empleado" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

//en esta parte del codigo recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos

if (!empty($_POST))
{
	$alert='';
	if (empty($_POST['usuario']))
	{
			$alert= '<p class="msg_error">No ingreso Usuario </p>';
	}else
	{
			$cod_persona 			 = $_POST['cod_persona'];
			$cod_empleado 		 = $_POST['id'];
			$cod_tipo_empleado = $_POST['cod_tipo_empleado'];
			$persona					 = $_POST['persona'];
			$empleado          =$_POST['tipo_empleado'];
			$usuario					 =$_POST['usuario'];
			$clave				     = md5($_POST['clave']);



			$query = mysqli_query($conection,"SELECT * from empleado e
																				where (e.usuario_empleado = '$usuario' AND e.tipo_empleado_cod_tipo_empleado = '$empleado' and e.persona_cod_persona = '$persona') and e.cod_empleado != '$cod_empleado'");

			$result3= mysqli_fetch_array($query);




			if ($result3 > 0)
			{
				$alert = '<p class="msg_error">Persona o Usuario ya existente</p>';
			}else
			{

				if (empty($_POST['clave']))
				{
					$sql_update = mysqli_query($conection, "UPDATE empleado
																									SET persona_cod_persona = '$cod_persona',tipo_empleado_cod_tipo_empleado = '$cod_tipo_empleado',usuario_empleado = '$usuario'
																									where cod_empleado = $cod_empleado");
				}else
				{
					$sql_update = mysqli_query($conection, "UPDATE empleado
																									SET persona_cod_persona = $cod_persona,tipo_empleado_cod_tipo_empleado = $cod_tipo_empleado,usuario_empleado = '$usuario',pass_empleado = '$clave'
																									where cod_empleado = $cod_empleado");
				}


				if ($sql_update)
				{
						$alert = '<p class="msg_save">Usuario actulizado correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al actualizar el usuario</p>';
				}
			}

	}

}
//fin de la parte del codigo donde recibimos lo que manda el form y realizamos la funcion de actualizar la base de datos


//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
//correcta el form

if (empty($_REQUEST['id'])) {

  header('location: lista_usuario.php');

}

$codEmpleado = $_REQUEST['id'];



$sql = mysqli_query($conection,"SELECT e.cod_empleado,p.nombre_persona,p.apellido_persona,t.descripcion_empleado,e.usuario_empleado, e.tipo_empleado_cod_tipo_empleado,e.persona_cod_persona
															  from empleado e
																INNER JOIN persona p ON e.persona_cod_persona = p.cod_persona
																INNER JOIN tipo_empleado t ON e.tipo_empleado_cod_tipo_empleado = t.cod_tipo_empleado
																WHERE e.cod_empleado = $codEmpleado");




$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('location: lista_usuario.php');
		  mysqli_close($conection);

}else {
	$option = '';
	$option2 = '';

  while ($data = mysqli_fetch_array($sql)) {
			$cod_empleado1   = $data['cod_empleado'];
			$nombre          = $data['nombre_persona'];
			$apellido        = $data['apellido_persona'];
			$tipo_empleado   = $data['descripcion_empleado'];
			$usuario         = $data['usuario_empleado'];
			$cod_empleado    = $data['tipo_empleado_cod_tipo_empleado'];
			$cod_persona     = $data['persona_cod_persona'];

			if ($tipo_empleado == 1) {
				  $option = '<option value="'.$tipo_empleado.'" select> '.$tipo_empleado.'</option>';
			}else {
				  $option = '<option value="'.$tipo_empleado.'"select> '.$tipo_empleado.'</option>';
			}
			//en esta parte del codigo trabajamos para envíar el id de algunas tablas para mostrar en el form lo que trae el select y algunas cosas mas para configurar de manera
			//correcta el form
			//////////////**///////////////////////////
			//acá debajo va el que trae el nombre_persona

			$sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro
																							FROM persona");

			$result_register = mysqli_fetch_array($sql_register);
			$total_registro = $result_register['total_registro'];

			$i=1;
				while ($cod_persona != $i) {
						$option2 =  '<option value=' . $cod_persona. '>' . $nombre . ' ' . $apellido  .  '</option>';
						$i++;
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
	<title>Actualizar usuario</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-edit"></i> Actualizar Usuario</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
					<input type="hidden" name="id" value="<?php echo $cod_empleado1; ?>">

					<input type="hidden" name="cod_persona" value=<?php echo $cod_persona; ?>>

					<input type="hidden" name="cod_tipo_empleado" value=<?php echo $cod_empleado; ?>>

          <label for="persona">Seleccione al Usuario:</label>
          <select id="persona"  class="notItemOne" name="persona" value= <?php echo $nombre ." ".$apellido; ?>>
            <?php
							echo $option2;
              while ($persona = mysqli_fetch_array($result)) {
                echo '<option value=' . $persona['cod_persona']. '>' . $persona['nombre_persona'] . ' ' . $persona['apellido_persona']  . ' - ' . $persona['ci_persona'] . '</option>';
              }
             ?>

					<a href="lista_personaActualizar.php" class="btn_search"><i class="fas fa-search"></i> Buscar una persona</a>

          </select>
					<label for="tipo_empleado">Seleccione el tipo de usuario:</label>
          <select id="tipo_empleado"  class="notItemOne" name="tipo_empleado" value= <?php echo $tipo_empleado; ?>>
            <?php
 							echo $option;
              while ($tipo_empleado = mysqli_fetch_array($result2)) {
                echo '<option value=' . $tipo_empleado[cod_tipo_empleado]. '>' . $tipo_empleado[descripcion_empleado] . '</option>';
              }
             ?>
          </select>

					<label for="usuario">Usuario</label>
					<input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
					<label for="clave">Clave</label>
					<input type="password" name="clave" id="clave" placeholder="Ingrese clave de acceso">

					 <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar Usuario</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
