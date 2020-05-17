<?php

	//print_r($_REQUEST);
	//exit;
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../../conexion.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;

	if(empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{

		$noFactura = $_REQUEST['f'];
		$anulada = '';



		$query = mysqli_query($conection," SELECT v.venta_id,a.accesorio_descripcion,a.accesorio_nombre,
	    d.precio_detalle_factura,l.lado_descripcion,ma.marca_descripcion,m.modelo_descripcion
			FROM venta v
      INNER JOIN detalle_factura d ON d.venta_id = v.venta_id
      INNER JOIN accesorio a ON d.accesorio_id = a.accesorio_id
			INNER JOIN modelo m ON a.modelo_id = m.modelo_id
			INNER JOIN marca ma ON m.marca_id = ma.marca_id
			INNER JOIN lado l ON a.lado_id = l.lado_id

			WHERE v.venta_id = $noFactura ");


		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$no_factura = $factura['venta_id'];
			echo $no_factura;
			/*if($factura['status'] == 2){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}*/

			$query_productos = mysqli_query($conection,"SELECT v.venta_id,a.accesorio_descripcion,a.accesorio_nombre,
	    d.precio_detalle_factura,l.lado_descripcion,ma.marca_descripcion,m.modelo_descripcion
			FROM venta v
      INNER JOIN detalle_factura d ON d.venta_id = v.venta_id
      INNER JOIN accesorio a ON d.accesorio_id = a.accesorio_id
			INNER JOIN modelo m ON a.modelo_id = m.modelo_id
			INNER JOIN marca ma ON m.marca_id = ma.marca_id
			INNER JOIN lado l ON a.lado_id = l.lado_id
			WHERE v.venta_id = $no_factura ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
		    include(dirname('__FILE__').'/factura.php');
		    $html = ob_get_clean();

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('factura_'.$noFactura.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>
