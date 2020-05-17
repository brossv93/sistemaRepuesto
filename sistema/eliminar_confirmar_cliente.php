<?php
session_start();

include "../conexion.php";
if (!empty($_POST))
{
  $idCliente = $_POST['id'];

  $query_delete = mysqli_query($conection,"DELETE FROM cliente where cod_cliente = $idCliente");
  if ($query_delete) {
    header("location: lista_cliente.php");
  }else {
    echo "Error al eliminar cliente";
  }
}


if (empty($_SESSION['active'])) {
	header('location: ../');
}


if (empty($_REQUEST['id'])) {
  header("location: lista_cliente.php");
}else
{
  $cod_cliente = $_REQUEST['id'];


  $query = mysqli_query($conection,"SELECT c.empresa_cliente,c.ruc_cliente,c.cod_cliente
                                    from cliente c
                                    WHERE c.cod_cliente = $cod_cliente");
  //mysqli_close($conection);
  $result = mysqli_num_rows($query);
  if ($result > 0){
    while ($data = mysqli_fetch_array($query)) {
      $empresa      = $data[0];
      $ruc     = $data[1];
      $codigo      = $data[2];
    }

    }else
    {
      header("location: lista_cliente.php");
    }
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Eliminar Cliente</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
      <i class="fas fa-user-times fa-7x" style="color: red"></i>
      <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
      <p>Nombre: <span><?php echo "El cliente ". $empresa. ' con el ruc ' .$ruc ?></span></p>
      <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $codigo; ?>">
        <a href="lista_cliente.php" class="btn_cancel"><i class="fas fa-minus-circle"></i> Cancelar</a>
        <button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
