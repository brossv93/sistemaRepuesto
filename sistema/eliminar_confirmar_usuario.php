<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include "../conexion.php";
if (!empty($_POST))
{
  $idUsuario = $_POST['cod_empleado'];

  $query_delete = mysqli_query($conection,"DELETE FROM empleado where cod_empleado = $idUsuario");
  if ($query_delete) {
    header("location: lista_usuario.php");
  }else {
    echo "Error al eliminar usuario";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_usuario.php");
}else
{
  $cod_empleado = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT p.nombre_persona,p.apellido_persona,e.usuario_empleado,t.descripcion_empleado
                                    from empleado e
                                    INNER JOIN persona p
                                    ON e.persona_cod_persona = p.cod_persona
                                    INNER JOIN tipo_empleado t
                                    ON e.tipo_empleado_cod_tipo_empleado = t.cod_tipo_empleado
                                    WHERE e.cod_empleado = $cod_empleado");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $nombre       = $data[0];
      $apellido     = $data[1];
      $usuario      = $data[2];
      $tipoEmpleado = $data[3];
    }

    }else
    {
      header("location: lista_usuario.php");
    }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Eliminar Usuario</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<i class="fas fa-user-times fa-7x" style="color: red"></i>
      <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
      <p>Nombre: <span><?php echo $nombre. ' ' .$apellido ?></span></p>
      <p>Usuario: <span><?php echo $usuario ?></span></p>
      <form method="post" action="">
        <input type="hidden" name="cod_empleado" value="<?php echo $cod_empleado; ?>">
        <a href="lista_usuario.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>

				<button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
