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
	<title>Lista de Clientes</title>
</head>
<body>

<?php include "includes/header.php"; ?>
	<section id="container">
    <?php
      ////acá vamos a trabajar lo que trae el buscar
      $busqueda = mb_strtolower($_REQUEST['busqueda']);
      if (empty($busqueda)) {
        header("location: lista_cliente.php");
			  mysqli_close($conection);
      }
     ?>
		<h1><i class="fas fa-clipboard-list"></i> Lista de Clientes</h1>
		<a href="registro_cliente.php" class="btn_new"><i class="fas fa-user-plus"></i> Crear Cliente</a>

		<form action="buscar_cliente.php" method="get" class="form_search">

			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>

		</form>

		<table>
			<tr>
				<th>Id</th>
				<th>Ruc</th>
				<th>Direccion</th>
        <th>Telefono</th>
        <th>Empresa</th>
        <th>Correo</th>
        <th>Contacto</th>
				<th>Acciones</th>
			</tr>

			<?php

			////paginador

				$sql_register = mysqli_query($conection,"SELECT count(*) as total_registro,c.contacto_cliente,c.ruc_cliente,c.cod_cliente,c.telefono_cliente,c.correo_cliente
                                                ,c.empresa_cliente,c.direccion_cliente
																								 from cliente c
																								 WHERE
																								 (c.cod_cliente LIKE '%$busqueda%' OR
																									 c.contacto_cliente LIKE '%$busqueda%' OR
																									 c.ruc_cliente LIKE '%$busqueda%' OR
                                                   c.empresa_cliente LIKE '%$busqueda%' OR
                                                   c.direccion_cliente LIKE '%$busqueda%' OR
                                                   c.correo_cliente LIKE '%$busqueda%' OR
																									 c.telefono_cliente LIKE '%$busqueda%')");

				$result_register = mysqli_fetch_array($sql_register);
				$total_registro = $result_register['total_registro'];

				$por_pagina = 5;

				if (empty($_GET['pagina']))
				{
						$pagina = 1;
				}else {
					$pagina = $_GET['pagina'];
				}

				$desde = ($pagina-1) * $pagina;
				$total_paginas = ceil($total_registro / $por_pagina);

					$query = mysqli_query($conection, "SELECT c.cod_cliente,c.ruc_cliente,c.direccion_cliente,c.telefono_cliente,c.empresa_cliente,c.correo_cliente,c.contacto_cliente
  																					 from cliente c
  																					 WHERE
  																								(c.cod_cliente LIKE '%$busqueda%' OR
  																								 c.contacto_cliente LIKE '%$busqueda%' OR
																									 c.ruc_cliente LIKE '%$busqueda%' OR
                                                   c.empresa_cliente LIKE '%$busqueda%' OR
                                                   c.direccion_cliente LIKE '%$busqueda%' OR
                                                   c.correo_cliente LIKE '%$busqueda%' OR
  																								 c.telefono_cliente LIKE '%$busqueda%')
																				 	   LIMIT $desde,$por_pagina");
					mysqli_close($conection);
					$result = mysqli_num_rows($query);
					if ($result >0 )
					{
						while ($data = mysqli_fetch_array($query))
						{



      ?>

			<tr>
				<td><?php echo $data[0]; ?></td>
				<td><?php echo $data[1]; ?></td>
				<td><?php echo $data[2]; ?></td>
				<td><?php echo $data[3]; ?></td>
        <td><?php echo $data[4]; ?></td>
        <td><?php echo $data[5]; ?></td>
        <td><?php print nl2br($data[6]); ?></td>
				<td>
						<a class="link_edit" href="editar_cliente.php?id=<?php echo $data[0]; ?>"><i class="fas fa-edit"></i> Editar</a>
						|
						<a class="link_delete" href="eliminar_confirmar_cliente.php?id=<?php echo $data[0]; ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
			  </td>

			</tr>

     <?php
		      }
	       }
     ?>

		</table>
		<?php
			if ($total_registro !=0) {


		 ?>
		<div class="paginador">
			<ul>
				<?php

						if ($pagina != 1)
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
								echo '<li> <a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i. '</a> </li>';
						}

					}

					if ($pagina != $total_paginas) {


				 ?>


				 <li> <a href="?pagina=<?php echo $pagina + 1; ?>"> <i class="fas fa-forward"></i> </a> </li>
	 		 <li> <a href="?pagina=<?php echo $total_paginas; ?>"> <i class="fas fa-fast-forward"></i> </a> </li>

				<?php } ?>
			</ul>
		</div>
		<?php
			}
		 ?>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
