<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Sistema Ventas</title>
</head>
<body>
<?php
		include "includes/header.php";
		include "../conexion.php";

		//datos de la empresa

		$ruc = '';
		$nombre = '';
		$razonSocial = '';
		$telefono = '';
		$email ='';
		$direccion = '';
		$iva = '';

		$query_empresa = mysqli_query($conection,"SELECT * FROM user");
		$row_empresa = mysqli_num_rows($query_empresa);


		$sql_lote = mysqli_query($conection,"SELECT COUNT(*) as total_lote
																						FROM lote");

		$result_lote = mysqli_fetch_array($sql_lote);
		$total_lote = $result_lote['total_lote'];


		$sql_accesorio = mysqli_query($conection,"SELECT COUNT(*) as total_accesorio
																						FROM accesorio");

		$result_accesorio = mysqli_fetch_array($sql_accesorio);
		$total_accesorio = $result_accesorio['total_accesorio'];

		$sql_venta = mysqli_query($conection,"SELECT COUNT(*) as total_venta
																						FROM venta");

		$result_venta = mysqli_fetch_array($sql_venta);
		$total_venta = $result_venta['total_venta'];


		if ($row_empresa > 0)
		{
			while ($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa))
			{
				$nombre = $arrInfoEmpresa['user_account'];
			}
		}
		//ejecuta procedimiento
	/*	$query_dash = mysqli_query($conection,"CALL dataDashBoard()");
		$result_dash = mysqli_num_rows($query_dash);
		if ($result_dash > 0)
		{
			$data_dash = mysqli_fetch_assoc($query_dash);
			mysqli_close($conection);
		}*/
?>
	<section id="container">
		<div class="divContainer">
			<div>
				<h1 class="titlePanelControl">Panel de Control</h1>
			</div>
			<div class="dashboard">


				<a href="lista_lote.php">
					<i class="fas fa-building"></i>
					<p>
						<strong>Lotes</strong><br>
						<span><?php echo $total_lote; ?></span>
					</p>
				</a>
				<a href="lista_producto.php">
					<i class="fas fa-cubes"></i>
					<p>
						<strong>Accesorios</strong><br>
						<span><?php echo $total_accesorio; ?></span>
					</p>
				</a>
				<a href="ventas.php">
					<i class="fas fa-building"></i>
					<p>
						<strong>Ventas</strong><br>
						<span><?php echo $total_venta; ?></span>
					</p>
				</a>
			</div>
		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
