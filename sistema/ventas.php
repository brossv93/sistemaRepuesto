<?php

session_start();


if (empty($_SESSION['active'])) {
	header('location: ../');
}
include "../conexion.php";
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Lista de Ventas</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<h1><i class="far fa-newspaper"></i> Lista de Ventas</h1>
		<a href="nueva_venta.php" class="btn_new"><i class="fas fa-plus"></i> Generar Factura</a>

		<form action="buscar_venta.php" method="get" class="form_search">

			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar por cliente o N° factura">
				<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
		</form>

    <div>
      <h5>Buscar por fecha</h5>
      <form  action="buscar_venta.php" method="get" class="form_search_date">
        <label>De: </label>
        <input type="date" name="fecha_de" id="fecha_de" required>
        <label>A </label>
        <input type="date" name="fecha_a" id="fecha_a" required>
        <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
      </form>
    </div>

		<table>
			<tr>
				<th>No.</th>
				<th>Fecha</th>
				<th>Estado</th>
				<th class="textright"> Total Factura</th>
        <th class="textright"> Acciones</th>
        </tr>

			<?php

			////paginador

				$sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro
																								FROM venta");

				$result_register = mysqli_fetch_array($sql_register);
				$total_registro = $result_register['total_registro'];

				$por_pagina = 15;

				if (empty($_GET['pagina']))
				{
						$pagina = 1;
				}else {
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);

					$query = mysqli_query($conection, "SELECT v.venta_id,v.venta_fecha,v.venta_precio

																							FROM venta v
			      																	ORDER BY venta_id
																						  LIMIT $desde,$por_pagina");


				  mysqli_close($conection);
					$result = mysqli_num_rows($query);
					if ($result >0 )
					{
						while ($data = mysqli_fetch_array($query))
						{

							///lo de abajo es prueba

							///fin prueba


                $status = '<span class = "pagada"> Pagada </span>';

        ?>

			<tr id="row_<?php echo $data['aux']; ?>">
				<td><?php echo $data[0]; ?></td>
				<td><?php echo $data[1]; ?></td>
				<td class="estado"><?php echo $status; ?></td>
        <td class="textright total_factura"><span>Gs.</span><?php echo number_format($data['venta_precio'],0,',','.'); ?></td>
        <td>
						<div class="div_acciones">
							<div>
									<button type="button" class="btn_view view_factura" f="<?php echo $data[0]; ?>"><i class="fas fa-eye"></i></button>
							</div>




						<div class="div_factura">
							<button class="btn_anular anular_factura" fac="<?php echo $data[0]; ?>"><i class="fas fa-ban"></i></button>
						</div>

					</div>
			  </td>

			</tr>

     <?php
		      }
	       }
     ?>

		</table>

		<div class="paginador">
			<ul>
				<?php
						if($pagina != 1)
						{

				?>
				<li> <a href="?pagina=<?php echo 1; ?>"> <i class="fas fa-fast-backward"></i> </a> </li>
				<li> <a href="?pagina=<?php echo $pagina - 1; ?>"> <i class="fas fa-backward"></i> </a> </li>

				     <?php
						}

					for ($i=1; $i <= $total_paginas; $i++)
					{
						if ($i == $pagina)
						{
							echo '<li class="pageSelected"> '.$i.' </li>';
						}else
						{
								echo '<li><a href="?pagina='.$i.'">'.$i.' </a></li>';
						}

					}
					if ($pagina != $total_paginas)
          {
				 ?>
				 <li> <a href="?pagina=<?php echo $pagina + 1; ?>"> <i class="fas fa-forward"></i> </a> </li>
 				<li> <a href="?pagina=<?php echo $total_paginas; ?>"> <i class="fas fa-fast-forward"></i> </a> </li>
				<?php } ?>
			</ul>
		</div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
