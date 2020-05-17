<?php
session_start();

include_once "../conexion.php";

//include_once "ajax2.php";


@$sql = "SELECT * from componente ORDER BY componente_id desc" or die ("error". mysqli_error($conection));
@$result = mysqli_query($conection, $sql);

@$sql2 = "SELECT m.modelo_id,m.modelo_descripcion,ma.marca_descripcion from modelo m, marca ma where m.marca_id = ma.marca_id" or die ("error". mysqli_error($conection));
@$result2 = mysqli_query($conection, $sql2);

@$sql3 = "SELECT * from lado" or die ("error". mysqli_error($conection));
@$result3 = mysqli_query($conection, $sql3);

@$sql4 = "SELECT * from lote" or die ("error". mysqli_error($conection));
@$result4 = mysqli_query($conection, $sql4);




////otra prueba

///fin prueba
//abajo de esto hago lo que pide el fomulario

if (!empty($_POST))
{

	$alert='';
	if (empty($_POST['cod_nombre_producto']) || empty($_POST['stock_producto']))
	{
			$alert= '<p class="msg_error">No ingreso algun campo obligatorio</p>';
	}else
	{
			$cod_nombre_producto        = $_POST['cod_nombre_producto'];
			$stock_producto	  					= $_POST['stock_producto'];
			$lado	  										=$_POST['lado_id'];
			$componente	  							=$_POST['componente_id'];
			$modelo	  									=$_POST['modelo_id'];
			$anho 											= $_POST['accesorio_anho'];
			$checkbox 									= $_POST['checkbox'];
			$lote 											= $_POST['lote_id'];

			echo $lote;

			foreach ($checkbox as $key => $value) {


		/*	$query = mysqli_query($conection,"SELECT * from accesorio
																				WHERE accesorio_nombre = '$cod_nombre_producto' or accesorio_descripcion = '$descripcion_producto'");*/

			/*$result3= mysqli_fetch_array($query);

			if ($result3 > 0)
			 {
				$alert = '<p class="msg_error">Articulo duplicado</p>';
			 }else
			 {*/
			 @$sql5 = "SELECT accesorio_componente_precio from accesorio_componente WHERE accesorio_componente_descripcion LIKE '$value' " or die ("error". mysqli_error($conection));
			 @$result5 = mysqli_query($conection, $sql5);

			 $precio = mysqli_fetch_array($result5);

				$query_insert = mysqli_query($conection,"INSERT INTO accesorio(accesorio_nombre,accesorio_anho,accesorio_descripcion,accesorio_precio,accesorio_stock,componente_id,modelo_id,lado_id,lote_id)
																																			VALUES('$cod_nombre_producto','$anho','$value','$precio[accesorio_componente_precio]','$stock_producto','$componente','$modelo','$lado','$lote')");
				if ($query_insert)
				{
            $alert = '<p class="msg_save">Insertado Correctamente</p>';
				}else
				{
						$alert = '<p class="msg_error">Error al crear el accesorio</p>';
				}
			//}
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

					<label for="accesorio_anho">Año de fabricación: </label>
					<input type="date" name="accesorio_anho" id="accesorio_anho" placeholder="Ingrese direccion de cliente">



					<label for="stock_producto">Cantidad</label>
					<input type="text" name="stock_producto" id="stock_producto" placeholder="Cantidad a ingresar">

					<label for="componente_id">Seleccione el componente:</label>
					<select id="componente_id" name="componente_id" onclick="seleccionaComponente();">
            <?php

              while ($componente = mysqli_fetch_array($result)) {
                echo '<option  id="componente_valor" value=' . $componente[componente_id]. '>' . "$componente[componente_descripcion]" . '</option>';
              }
             ?>
          </select><a href="registro_accesorio_componente.php" class="btn_addMore"><i class="fas fa-plus-square"></i></a>

					<div id="componenteSeleccionado"></div>



					<label for="proveedor">Seleccione el modelo con su marca:</label>
					<select id="modelo_id" name="modelo_id">
            <?php

              while ($modelo = mysqli_fetch_array($result2)) {
                echo '<option value=' . $modelo[modelo_id] .'>' . $modelo[modelo_descripcion] . "--" . $modelo[marca_descripcion] . '</option>';
              }
             ?>
          </select>

					<label for="lado_id">Seleccione el lado:</label>
					<select id="lado_id" name="lado_id">
            <?php
              while ($lado = mysqli_fetch_array($result3)) {
                echo '<option value=' . $lado[lado_id]. '>' . $lado[lado_descripcion] . '</option>';
              }
             ?>
          </select>

					<label for="lote_id">Seleccione el lote:</label>
					<select id="lote_id" name="lote_id">
            <?php
              while ($lote = mysqli_fetch_array($result4)) {
                echo '<option value=' . $lote[lote_id]. '>' . $lote[lote_descripcion] . '</option>';
              }
             ?>
          </select>

					<div id="form_alert"></div>
          </div>


					 <button type="submit"class="btn_save"><i class="fas fa-save"></i> Crear Producto</button>
				</form>
				<script>
						var expanded=false;
						function showCheckboxes(){
							var checkboxes = document.getElementById("checkboxes");
							if (!expanded)
							{
									checkboxes.style.display="block";
									expanded=true;
							}else{
								checkboxes.style.display="none";
								expanded=false;
							}
						}

							function seleccionaComponente(){

								var componenteId = $('#componente_id').val();
								var componente_id =document.getElementById('#componente_id');
								//var componenteId = componente_id.value;

								document.getElementById("componenteSeleccionado").innerText = componenteId;

								var xhttp = new XMLHttpRequest();
								xhttp.onreadystatechange = function(){
									if (xhttp.readyState == 4 && xhttp.status == 200) {
										document.getElementById("componenteSeleccionado").innerHTML = xhttp.responseText;
									}
								};
								xhttp.open("POST","ajax2.php",true);
								xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
								xhttp.send("componente_id2="+componenteId);
							}


				</script>
      </div>

	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>
