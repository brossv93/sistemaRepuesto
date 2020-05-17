<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include "../conexion.php";
if (!empty($_POST))
{
  $idtipoLote = $_POST['cod_tipoLote'];

  $query_delete = mysqli_query($conection,"DELETE FROM tipoLote where tipoLote_id = $idtipoLote");
  if ($query_delete) {
    header("location: lista_tipoLote.php");
  }else {
    echo "Error al eliminar registro";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_tipoLote.php");
}else
{
  $cod_tipoLote = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT *
                                    from tipoLote
                                    WHERE tipoLote_id = $cod_tipoLote");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $cod_tipoLote  = $data[0];
      $nombre       = $data[1];

    }

    }else
    {
      header("location: lista_tipoLote.php");
    }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Eliminar Registro</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
				<i class="fas fa-user-times fa-7x" style="color: red"></i>
      <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
      <p>Nombre: <span><?php echo $nombre ?></span></p>
      <form method="post" action="">
        <input type="hidden" name="cod_tipoLote" value="<?php echo $cod_tipoLote; ?>">
        <a href="lista_tipoLote.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>
        <button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
