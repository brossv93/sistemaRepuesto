<?php

session_start();
if ($_SESSION['rol'] != 1)
{
	header("location:./");
}

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
	<title>Lista de Personas</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
		<h1><i class="fas fa-clipboard-list"></i> Lista dePersonas</h1>
		<a href="registro_persona.php" class="btn_new"><i class="fas fa-user-plus"></i> Crear Persona</a>

		<form action="buscar_persona.php" method="get" class="form_search">

			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_search"><i class="fas fa-search"></i></button>

		</form>

		<table>
			<tr>
				<th>Id</th>
				<th>Nombre Completo</th>
				<th>Telefono</th>
				<th>CI</th>
        <th>Correo</th>
        <th>Direccion</th>
				<th>Acciones</th>
			</tr>

			<?php

			////paginador

				$sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro
																								FROM persona");

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

					$query = mysqli_query($conection, "SELECT *
																						 from persona
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
				<td><?php echo $data[1] . ' ' . $data[2]; ?></td>
				<td><?php echo $data[3]; ?></td>
				<td><?php echo $data[4]; ?></td>
        <td><?php echo $data[5]; ?></td>
				<td><?php echo $data[6]; ?></td>
				<td>
					<a class="link_edit" href="registro_usuario.php?id=<?php echo $data[0]; ?>"><i class="fas fa-edit"></i> Seleccione Un Usuario</a>
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
								echo '<li> <a href="?pagina='.$i.'">'.$i.' </a> </li>';
						}

					}

					if ($pagina != $total_paginas) {


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
