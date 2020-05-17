<?php

session_start();


include "../conexion.php";


@$sql3 = "SELECT * from iva_producto" or die ("error". mysqli_error($conection));
@$result3 = mysqli_query($conection, $sql3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "includes/scripts.php";?>

	<title>Nueva Venta</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
      <div class="title_page">
        <h1><i class="fas fa-cube"></i> Nueva venta</h1>
      </div>

        <div class="datos_venta">
          <h4>Datos de Venta</h4>
          <div class="datos">
          <div class="wd50">

            <p><?php echo $_SESSION['user']; ?></p>
          </div>
          <div class="wd50">
            <label>Acciones</label>
            <div id="acciones_venta">
              <a href="#" class="btn_ok textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
              <a href="#" class="btn_new textcenter" id="btn_facturar_venta" style="display:none;"><i class="far fa-edit"></i> Procesar</a>
            </div>
          </div>
        </div>
      </div>

      <table class="tbl_venta">

        <thead>



				<!--	<tr>
            <th colspan="8">Remision</th>
          </tr>
					<tr>
						<th colspan="8"><textarea name="remision" id="remision" rows="1" cols="1" placeholder="Remision"></textarea></th>
					</tr>-->


        </thead>

        <thead>
          <tr>
            <th width="100px">Código</th>
            <th>Descripcion</th>
            <th>Existencia</th>
            <th width="100px">Cantidad</th>
            <th width="100px">Precio</th>
            <th class="textright">Precio Total</th>
            <th>Acción</th>
          </tr>
          <tr>
            <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
            <td id="txt_descripcion">-</td>
            <td id="txt_existencia">-</td>
            <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
            <td ><input type="text" name="txt_precio" id="txt_precio" value="0" min="1" disabled></td>
            <td id="txt_precio_total" class="textright">0.00</td>
            <td><a href="#" id="add_product_venta" class="link_add"><i class="fas fa-plus"></i> Agregar</td>
          </tr>
          <tr>
            <th colspan="2">Descripcion</th>
            <th>Cantidad</th>
            <th class="textright">Precio</th>
            <th class="textright">Precio Total</th>
            <th colspan="2">Acción</th>
          </tr>
        </thead>

        <tbody id="detalle_venta">
          <!--Contenido Ajax-->

        </tbody>


        <tfoot id="detalle_totales">
        <!--Contenido Ajax-->
        </tfoot>
      </table>
    </section>
    <?php include "includes/footer.php"; ?>
		<script type="text/javascript">
			$(document).ready(function()
			{
				var usuarioId = '<?php echo $_SESSION['idUser']; ?>';
				searchForDetalle(usuarioId);
			});
		</script>
  </body>
</html>
