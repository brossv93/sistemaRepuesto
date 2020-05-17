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

    <link rel="stylesheet" href="style.css">
</head>
<body >
<?php echo $anulada; ?>

<div id="page_pdf">

<?php
$aux = 0;


$between = "BETWEEN";
$posicion_coincidencia = strrpos($contabilidad, $between);
if($posicion_coincidencia !== false){


$mesDeContabilidad = str_replace("fecha_factura BETWEEN '","Desde: ",$contabilidad);
$mesDeContabilidad2 = str_replace(" 00:00:00' AND '"," Hasta: ",$mesDeContabilidad);
$mesDeContabilidad3 = str_replace("23:59:59'"," ",$mesDeContabilidad2);

}else {


  $mesDeContabilidad3 = "Filtrado por Codigo o Cliente";


}
 ?>

	<table id="factura_detalle2">

    <caption><h1>Lista de Facturas</h1></caption>
    <caption><?php echo $mesDeContabilidad3; ?></caption>
			<thead>

				<tr>
          <th width="50px">Cod Fact.</th>
					<th class="textcenter">Cliente</th>
          <th class="textcenter">Fecha</th>
          <th class="textright" width="150px">Exenta</th>
					<th class="textright" width="150px">Precio Total</th>
				</tr>
			</thead>
			<tbody  id="detalle_productos2" >

			<?php

				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
            $aux=$aux+1;
		          ?>

				<tr >
					<td class="textcenter"><?php echo $row['cod_factura']; ?></td>
					<td class="textcenter"><?php echo $row['cliente']; ?></td>
          <td class="textcenter"><?php echo $row['fecha_factura']; ?></td>
          <?php if ($row['iva_producto_cod_iva_producto'] == 4) {?>
              <td class="textright"><?php echo number_format($row['total_factura'],0,',','.'); ?></td>
              <?php $sumadorExcenta = $sumadorExcenta + $row['total_factura']; ?>
          <?php }else{ ?>
              <td class="textright"><?php echo 0; ?></td>
            <?php } ?>
            <?php if ($row['iva_producto_cod_iva_producto'] == 3 or $row['iva_producto_cod_iva_producto'] == 0 ) {?>
                <td class="textright"><?php echo number_format($row['total_factura'],0,',','.'); ?></td>
            <?php }else{ ?>
                <td class="textright"><?php echo 0; ?></td>
              <?php } ?>
				</tr>

			<?php
						$precio_total = $row['total_factura'];
						$subtotal = round($subtotal + $precio_total, 2);
            $aux=$aux+1;
					}
				}
        $iva=10;
				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto,2 );
				$total 		= round($tl_sniva + $impuesto,2);

			?>
			</tbody>
			<tfoot id="detalle_totales2">
				<tr>
					<td colspan="4" class="h5"><span>SUBTOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($tl_sniva,0,',','.'); ?></span></td>
				</tr>
				<tr>
					<td colspan="4" class="h5"><span>IVA (<?php echo $iva; ?> %)</span></td>
					<td class="textright"><span><?php echo number_format($impuesto,0,',','.'); ?></span></td>
				</tr>
        <tr>
					<td colspan="4" class="h5"><span>EXENTA</span></td>
					<td class="textright"><span><?php echo number_format($sumadorExcenta,0,',','.'); ?></span></td>
				</tr>
				<tr>
					<td colspan="4" class="h5"><span>TOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($total,0,',','.'); ?></span></td>
				</tr>
		</tfoot>
	</table>

  <?php if ($aux >30) {?>
  <table class="page-break" id="factura_detalle2" align="center">
      <thead>
        <tr>
          <th width="50px">Cod Fact.</th>
          <th class="textcenter">Cliente</th>
          <th class="textcenter">Fecha</th>
          <th class="textright" width="150px"> Exenta</th>
          <th class="textright" width="150px"> Precio Total</th>
        </tr>
      </thead>
      <tbody  id="detalle_productos2" >

      <?php

        if($result_detalle2 > 0){

          while ($row = mysqli_fetch_assoc($query_productos2)){
       ?>
        <tr >
          <td class="textcenter"><?php echo $row['cod_factura']; ?></td>
          <td><?php echo $row['cliente']; ?></td>
          <td><?php echo $row['fecha_factura']; ?></td>
          <?php if ($row['iva_producto_cod_iva_producto'] == 4) {?>
              <td class="textright"><?php echo number_format($row['total_factura'],0,',','.'); ?></td>
              <?php $sumadorExcenta = $sumadorExcenta + $row['total_factura']; ?>
          <?php }else{ ?>
              <td class="textright"><?php echo 0; ?></td>
            <?php } ?>
            <?php if ($row['iva_producto_cod_iva_producto'] == 3 or $row['iva_producto_cod_iva_producto'] == 0 ) {?>
                <td class="textright"><?php echo number_format($row['total_factura'],0,',','.'); ?></td>
            <?php }else{ ?>
                <td class="textright"><?php echo 0; ?></td>
              <?php } ?>
        </tr>

      <?php
            $precio_total = $row['total_factura'];
            $subtotal = round($subtotal + $precio_total, 2);
          }
        }
        $iva=10;
        $impuesto 	= round($subtotal * ($iva / 100), 2);
        $tl_sniva 	= round($subtotal - $impuesto,2 );
        $total 		= round($tl_sniva + $impuesto,2);
      ?>
      </tbody>
      <tfoot id="detalle_totales2">
        <tr>
          <td colspan="4" class="textright"><span>SUBTOTAL Gs.</span></td>
          <td class="textright"><span><?php echo number_format($tl_sniva,0,',','.'); ?></span></td>
        </tr>
        <tr>
          <td colspan="4" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
          <td class="textright"><span><?php echo number_format($impuesto,0,',','.'); ?></span></td>
        </tr>
        <tr>
					<td colspan="4" class="textright"><span>EXENTA</span></td>
					<td class="textright"><span><?php echo number_format($sumadorExcenta,0,',','.'); ?></span></td>
				</tr>
        <tr>
          <td colspan="4" class="textright"><span>TOTAL Gs.</span></td>
          <td class="textright"><span><?php echo number_format($total,0,',','.'); ?></span></td>
        </tr>
    </tfoot>
  </table>

<?php } ?>

</div>

</body>
</html>
