<?php
session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}
include "../conexion.php";
if (!empty($_POST))
{
  $idlado = $_POST['cod_lado'];

  $query_delete = mysqli_query($conection,"DELETE FROM lado where lado_id = $idlado");
  if ($query_delete) {
    header("location: lista_lado.php");
  }else {
    echo "Error al eliminar registro";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_lado.php");
}else
{
  $cod_lado = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT *
                                    from lado
                                    WHERE lado_id = $cod_lado");
  mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $cod_lado  = $data[0];
      $nombre       = $data[1];

    }

    }else
    {
      header("location: lista_lado.php");
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
        <input type="hidden" name="cod_lado" value="<?php echo $cod_lado; ?>">
        <a href="lista_lado.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>
        <button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
