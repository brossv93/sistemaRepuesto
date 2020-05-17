<?php
session_start();


include_once "../conexion.php";



@$sql = "SELECT * from componente" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);

@$sql2 = "SELECT m.modelo_id,m.modelo_descripcion,ma.marca_descripcion from modelo m, marca ma where m.marca_id = ma.marca_id" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

@$sql3 = "SELECT * from lado" or die ("error". mysqli_error($conection));
@$result3 = mysqli_query($conection, $sql3);

//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{

	$alert='';
	if (empty($_POST['cod_nombre_producto']) || empty($_POST['descripcion_producto']) || empty($_POST['stock_producto']))
	{
			$alert= '<p class="msg_error">No ingreso algun campo obligatorio</p>';
	}else
	{
			$cod_nombre_producto        = $_POST['cod_nombre_producto'];
			$descripcion_producto  =$_POST['descripcion_producto'];
			$precio_producto	  = $_POST['precio_proveedor'];
			$stock_producto	  	= $_POST['stock_producto'];
			$lado	  =$_POST['lado_id'];
			$color	  =$_POST['accesorio_color'];
			$modelo	  =$_POST['modelo_id'];
			$anho = $_POST['accesorio_anho'];



			$query = mysqli_query($conection,"SELECT * from accesorio
																				WHERE accesorio_nombre = '$cod_nombre_producto' or accesorio_descripcion = '$descripcion_producto'");

			$result3= mysqli_fetch_array($query);

			if ($result3 > 0)
			 {
				$alert = '<p class="msg_error">Articulo duplicado</p>';
			 }else
			 {
				$query_insert = mysqli_query($conection,"INSERT INTO accesorio(accesorio_nombre,accesorio_anho,accesorio_descripcion,accesorio_precio,accesorio_stock,accesorio_color,componente_id,modelo_id,lado_id,lote_id)
																																			VALUES('$cod_nombre_producto','$anho','$descripcion_producto','$precio_producto','$stock_producto','$color',1,'$modelo','$lado',1)");
				if ($query_insert)
				{
            $alert = '<p class="msg_save">Insertado Correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al crear el cliente</p>';
				}
			}
	}
//	mysqli_close($conection);
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Registro de producto</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Registrar Producto</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post" enctype="multipart/form-data">


					<label for="cod_nombre_producto">Codigo del Producto</label>
					<input type="text" name="cod_nombre_producto" id="cod_nombre_producto" placeholder="Insertar El cod abreviado del producto">
					<label for="descripcion_producto">Descripcion</label>
					<input type="text" name="descripcion_producto" id="descripcion_producto" placeholder="Ingrese direccion de cliente">

					<label for="accesorio_anho">Año de fabricación: </label>
					<input type="date" name="accesorio_anho" id="accesorio_anho" placeholder="Ingrese direccion de cliente">

          <label for="precio_proveedor">Precio</label>
					<input type="text" name="precio_proveedor" id="precio_proveedor" placeholder="Ingrese Precio">

					<label for="stock_producto">Cantidad</label>
					<input type="text" name="stock_producto" id="stock_producto" placeholder="Cantidad a ingresar">

					<label for="stock_producto">Color</label>
					<input type="text" name="accesorio_color" id="accesorio_color" placeholder="Color a ingresar">


					<label for="proveedor">Seleccione el modelo con su marca:</label>
					<select id="modelo_id" name="modelo_id">
            <?php
              while ($modelo = mysqli_fetch_array($result2)) {
                echo '<option value=' . $modelo[modelo_id] .'>' . $modelo[modelo_descripcion] . "--" . $modelo[marca_descripcion] . '</option>';
              }
             ?>
          </select>

					<label for="lado">Seleccione el lado:</label>

					<select id="lado_id" name="lado_id">
            <?php
              while ($lado = mysqli_fetch_array($result3)) {
                echo '<option value=' . $lado[lado_id]. '>' . $lado[lado_descripcion] . '</option>';
              }
             ?>
          </select>

					<div id="form_alert"></div>
          </div>


					 <button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Producto</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
