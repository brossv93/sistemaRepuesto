<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include "../conexion.php";
if (!empty($_POST))
{
  $idmarca = $_POST['cod_marca'];

  $query_delete = mysqli_query($conection,"DELETE FROM marca where marca_id = $idmarca");
  if ($query_delete) {
    header("location: lista_marca.php");
  }else {
    echo "Error al eliminar registro";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_marca.php");
}else
{
  $cod_marca = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT *
                                    from marca
                                    WHERE marca_id = $cod_marca");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $cod_marca  = $data[0];
      $nombre       = $data[1];

    }

    }else
    {
      header("location: lista_marca.php");
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
        <input type="hidden" name="cod_marca" value="<?php echo $cod_marca; ?>">
        <a href="lista_marca.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>
        <button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
