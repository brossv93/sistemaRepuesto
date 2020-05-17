<?php
  include("numerosAletras.php");
	error_reporting(0);
	$subtotal 	= 0;
	$iva 	 	= 0;
	$impuesto 	= 0;
	$tl_sniva   = 0;
	$total 		= 0;
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="">
				</div>
			</td>

	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Cliente</span>
					<table class="datos_cliente">
						<tr>
							<td><label><span class="h4"><b>Factura N°:</b></span></label> <p><?php echo $factura['venta_id'];  ?></p></td>
              <td><label><span class="h4"><b>Accesorio:</b></span></label> <p><?php echo $factura['accesorio_descripcion'];  ?></p></td>
						</tr>
            <tr>
              <td><label><span class="h4"><b>Modelo:</b></span></label> <p><?php echo $factura['modelo_descripcion'];  ?></p></td>
              <td><label><span class="h4"><b>Accesorio:</b></span></label> <p><?php echo $factura['marca_descripcion'];  ?></p></td>
            </tr>
            <tr>
              <td><label><span class="h4"><b>Lado:</b></span></label> <p><?php echo $factura['lado_descripcion'];  ?></p></td>
              <td><label><span class="h4"><b>Color:</b></span></label> <p><?php echo "todavía no tengo color";  ?></p></td>
            </tr>
            <tr>
              <td><label><span class="h4"><b>Precio Gs:</b></span></label> <p><?php echo $factura['precio_detalle_factura'];  ?></p></td>
            </tr>
					</table>
				</div>
			</td>

		</tr>
	</table>



		</tfoot>
	</table>
	<table id="totales">

		</tr>
	</table>

</div>

</body>
</html>
