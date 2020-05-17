<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include "../conexion.php";
if (!empty($_POST))
{
  $idModelo = $_POST['modelo_id'];

  $query_delete = mysqli_query($conection,"DELETE FROM modelo where modelo_id = $idModelo");
  if ($query_delete) {
    header("location: lista_modelo.php");
  }else {
    echo "Error al eliminar modelo";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_modelo.php");
}else
{
  $cod_modelo = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT e.modelo_descripcion,p.marca_descripcion
                                    from modelo e
                                    INNER JOIN marca p
                                    ON e.marca_id = p.marca_id
                                    WHERE e.modelo_id = $cod_modelo");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $nombre       = $data[0];
      $marca     = $data[1];
      }

    }else
    {
      header("location: eliminar_confirmar_modelo.php");
    }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Eliminar Modelo</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<i class="fas fa-user-times fa-7x" style="color: red"></i>
      <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
      <p>Modelo: <span><?php echo $nombre; ?></span></p>
      <p>de marca: <span><?php echo $marca; ?></span></p>
      <form method="post" action="">
        <input type="hidden" name="modelo_id" value="<?php echo $cod_modelo; ?>">
        <a href="lista_modelo.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>

				<button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
