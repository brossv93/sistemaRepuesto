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
	<title>Lista de Productos</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<h1><i class="fas fa-clipboard-list"></i> Lista de Productos</h1>
		<a href="registro_producto.php" class="btn_new"><i class="fas fa-user-plus"></i> Minorista</a>
		<a href="registro_producto_mayorista.php" class="btn_new"><i class="fas fa-user-plus"></i> Mayorista</a>
		<form action="buscar_producto.php" method="get" class="form_search">

			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<input type="text" name="busqueda2" id="busqueda2" placeholder="Buscar">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>

		</form>


		<table>
			<tr>
				<th>Id Accesorio</th>
				<th>Cod Nombre</th>
				<th>Descripcion</th>
				<th>Anho</th>
				<th>fecha ingreso</th>
				<th>Color</th>
				<th>Componente</th>
				<th>Modelo</th>
				<th>Marca</th>
				<th>Lado</th>
				<th>Lote</th>
				<th>Precio</th>
        <th>Stock</th>
        <th>Acciones</th>
			</tr>

			<?php

			////paginador

				$sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro
																								FROM accesorio");

				$result_register = mysqli_fetch_array($sql_register);
				$total_registro = $result_register['total_registro'];
				
				$por_pagina = 5;

				if (empty($_GET['pagina']))
				{
						$pagina = 1;
				}else {
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);

					$query = mysqli_query($conection, "SELECT a.accesorio_id,a.accesorio_nombre,a.accesorio_anho,
																						 a.accesorio_descripcion,a.accesorio_precio,a.accesorio_stock,
																						 a.accesorio_fechaIng,c.componente_descripcion,m.modelo_descripcion,
																						 ma.marca_descripcion,l.lado_descripcion,a.accesorio_color
																						 ,v.lote_descripcion
																						 from accesorio a
																						 INNER JOIN componente c ON a.componente_id = c.componente_id
																						 INNER JOIN modelo m ON a.modelo_id = m.modelo_id
																						 INNER JOIN marca ma ON m.modelo_id = ma.marca_id
																						 INNER JOIN lado l ON a.lado_id = l.lado_id
																						 INNER JOIN lote v ON a.lote_id = v.lote_id
																						 ORDER BY accesorio_id
																						 LIMIT $desde,$por_pagina");

				  mysqli_close($conection);
					$result = mysqli_num_rows($query);
					if ($result >0 )
					{
						while ($data = mysqli_fetch_array($query))
						{

        ?>

			<tr class="row<?php echo $data[0]; ?>">
				<td><?php echo $data[0]; ?></td>
				<td><?php echo $data[1]; ?></td>
				<td><?php echo $data[3]; ?></td>
				<td><?php echo $data[2]; ?></td>
				<td><?php echo $data[6]; ?></td>
				<td><?php echo $data['accesorio_color']; ?></td>
				<td><?php echo $data[7]; ?></td>
				<td><?php echo $data[8]; ?></td>
				<td><?php echo $data[9]; ?></td>
				<td><?php echo $data[10]; ?></td>
				<td><?php echo $data['lote_descripcion']; ?></td>

				<td><?php echo $data[4]; ?></td>
				<td class="celStock"><?php echo $data[5]; ?></td>

				<td>
            <a class="link_add add_product" product="<?php echo $data[0]; ?>" href="#"><i class="fas fa-plus"></i></a>
            |
						<a class="link_edit" href="editar_producto.php?id=<?php echo $data[0]; ?>"><i class="fas fa-edit"></i></a>
						|
						<a class="link_delete del_product" href="#" product="<?php echo $data[0]; ?>" ><i class="fas fa-trash-alt"></i></a>
						|
						<button type="button" id="btn_detalle_producto" class="link_add view_factura2" f="<?php echo $data[0]; ?>"><i class="fas fa-print"></i></button>
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
