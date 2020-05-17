<?php
session_start();


include_once "../conexion.php";



@$sql = "SELECT * from componente" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);

@$sql2 = "SELECT * from modelo m,marca ma where m.marca_id = ma.marca_id" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

@$sql3 = "SELECT * from lado" or die ("error". mysqli_error($conection));
@$result3 = mysqli_query($conection, $sql3);

@$sql4 = "SELECT * from lote" or die ("error". mysqli_error($conection));
@$result4 = mysqli_query($conection, $sql4);

//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{

	$alert='';
	if (empty($_POST['cod_nombre_producto']) || empty($_POST['descripcion_producto']) )
	{
			$alert= '<p class="msg_error">No ingreso algun campo obligatorio</p>';
	}else
	{
      $codProducto 					 = $_POST['id'];
			$cod_nombre_producto   = $_POST['cod_nombre_producto'];
			$descripcion_producto  =$_POST['descripcion_producto'];
			$precio_producto	  	 = $_POST['precio_proveedor'];
			$componente            =$_POST['componente_id'];
			$color            		 =$_POST['accesorio_color'];
			$modelo								 =$_POST['modelo_id'];
			$lado                  =$_POST['lado_id'];
			$lote                  =$_POST['lote_id'];

			$query_update = mysqli_query($conection,"UPDATE accesorio
                                               SET    accesorio_nombre      = '$cod_nombre_producto',
                                                      accesorio_descripcion = '$descripcion_producto',
																											accesorio_precio 			= $precio_producto,
																											accesorio_color       = '$color',
																											componente_id         = $componente,
                                                      modelo_id             = $modelo,
      																							  lado_id               = $lado,
																											lote_id               = $lote
                                                      WHERE accesorio_id = $codProducto");

			if ($query_update)
      {
 				$alert='<p class="msg_save">Producto actualizado correctamente</p>';
			}else
  		{
     		$alert='<p class="msg_error">Error al actualizar</p>';
  		}
//	mysqli_close($conection);
}
}
//validar productos
if (empty($_REQUEST['id']))
{
  header("location: lista_producto.php");
}else
{
  $id_producto = $_REQUEST['id'];
  if (!is_numeric($id_producto))
  {
      header("location: lista_producto.php");

  }
  $query_producto = mysqli_query($conection,"SELECT a.accesorio_id,a.accesorio_nombre,a.accesorio_anho,a.accesorio_descripcion
																						 ,a.accesorio_precio,a.accesorio_stock,a.accesorio_fechaIng,
																						 c.componente_descripcion,m.modelo_descripcion,ma.marca_descripcion,
																						 l.lado_descripcion,c.componente_id,m.modelo_id,
																						 l.lado_id,a.accesorio_color,u.lote_descripcion
																		 				 from accesorio a
																		 			 	 INNER JOIN componente c ON a.componente_id = c.componente_id
																		 			 	 INNER JOIN modelo m ON a.modelo_id = m.modelo_id
																		 			 	 INNER JOIN marca ma ON m.modelo_id = ma.marca_id
																		 			 	 INNER JOIN lado l ON a.lado_id = l.lado_id
																						 INNER JOIN lote u ON a.lote_id = u.lote_id
                                             WHERE a.accesorio_id = $id_producto");
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto >0)
  {
    $data_producto = mysqli_fetch_assoc($query_producto);

  }else
  {
      header("location: lista_producto.php");
  }


}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>
	</script>
	<title>Editar producto</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

      <div class="form_register">
        <h1><i class="fas fa-user-plus"></i> Editar Producto</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $data_producto['accesorio_id']; ?>">


					<label for="cod_nombre_producto">Codigo del Producto</label>
					<input type="text" name="cod_nombre_producto" id="cod_nombre_producto" placeholder="Insertar El cod abreviado del producto" value="<?php echo $data_producto['accesorio_nombre']; ?>">

					<label for="descripcion_producto">Descripcion</label>
					<input type="text" name="descripcion_producto" id="descripcion_producto" placeholder="Ingrese direccion de cliente" value="<?php echo $data_producto['accesorio_descripcion']; ?>" >

					<label for="precio_proveedor">Precio</label>
					<input type="text" name="precio_proveedor" id="precio_proveedor" placeholder="Ingrese Precio" value="<?php echo $data_producto['accesorio_precio']; ?>">

					<label for="precio_proveedor">Color</label>
					<input type="text" name="accesorio_color" id="accesorio_color" placeholder="Ingrese Precio" value="<?php echo $data_producto['accesorio_color']; ?>">

					<label for="componente_id">Seleccione al componente</label>
          <select id="componente_id" name="componente_id" class="notItemOne">
            <option value="<?php echo $data_producto['componente_id']; ?>"selected><?php echo $data_producto['componente_descripcion']; ?></option>
            <?php
              while ($proveedor = mysqli_fetch_array($result)) {
                echo '<option value=' . $proveedor[componente_id]. '>' . $proveedor[componente_descripcion] . '</option>';
              }
             ?>
          </select>

					<label for="modelo_id">Seleccione al modelo</label>
          <select id="modelo_id" name="modelo_id" class="notItemOne">
            <option value="<?php echo $data_producto['modelo_id']; ?>"selected><?php echo $data_producto['modelo_descripcion']." -- ".$data_producto['marca_descripcion']; ?></option>
            <?php
              while ($proveedor2 = mysqli_fetch_array($result2)) {
                echo '<option value=' . $proveedor2[modelo_id]. '>' . $proveedor2[modelo_descripcion] . '---' . $proveedor2[marca_descripcion]  . '</option>';
              }
             ?>
          </select>

					<label for="lado_id">Seleccione lado</label>
          <select id="componente_id" name="lado_id" class="notItemOne">
            <option value="<?php echo $data_producto['lado_id']; ?>"selected><?php echo $data_producto['lado_descripcion']; ?></option>
            <?php
              while ($proveedor3 = mysqli_fetch_array($result3)) {
                echo '<option value=' . $proveedor3[lado_id]. '>' . $proveedor3[lado_descripcion] . '</option>';
              }
             ?>
          </select>

					<label for="lado_id">Seleccione lote/label>
          <select id="componente_id" name="lote_id" class="notItemOne">
            <option value="<?php echo $data_producto['lote_id']; ?>"selected><?php echo $data_producto['lote_descripcion']; ?></option>
            <?php
              while ($proveedor4 = mysqli_fetch_array($result4)) {
                echo '<option value=' . $proveedor4[lote_id]. '>' . $proveedor4[lote_descripcion] . '</option>';
              }
             ?>
          </select>

          <div id="form_alert"></div>
</div>


					 <button type="submit"class="btn_save"><i class="fas fa-save"></i> Actualizar Producto</button>
				</form>

      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
