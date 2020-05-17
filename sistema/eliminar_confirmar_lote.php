<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include "../conexion.php";
if (!empty($_POST))
{
  $idlote = $_POST['lote_id'];

  $query_delete = mysqli_query($conection,"DELETE FROM lote where lote_id = $idlote");
  if ($query_delete) {
    header("location: lista_lote.php");
  }else {
    echo "Error al eliminar lote";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_lote.php");
}else
{
  $cod_lote = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT e.lote_descripcion,p.tipoLote_descripcion
                                    from lote e
                                    INNER JOIN tipoLote p
                                    ON e.tipoLote_id = p.tipoLote_id
                                    WHERE e.lote_id = $cod_lote");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $nombre       = $data[0];
      $tipoLote     = $data[1];
      }

    }else
    {
      header("location: eliminar_confirmar_lote.php");
    }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Eliminar lote</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<i class="fas fa-user-times fa-7x" style="color: red"></i>
      <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
      <p>lote: <span><?php echo $nombre; ?></span></p>
      <p>de tipoLote: <span><?php echo $tipoLote; ?></span></p>
      <form method="post" action="">
        <input type="hidden" name="lote_id" value="<?php echo $cod_lote; ?>">
        <a href="lista_lote.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>

				<button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
