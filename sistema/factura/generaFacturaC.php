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
		echo "No es posible generar la contabilidad.";
	}else{


		$contabilidad = $_REQUEST['f'];


		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}


		$query = mysqli_query($conection,"SELECT f.cod_factura, DATE_FORMAT(f.fecha_factura, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha_factura,'%H:%i:%s') as  hora, f.cliente_cod_cliente, f.status,f.total_factura,
												 v.usuario_empleado as vendedor,
												 cl.ruc_cliente, cl.empresa_cliente, cl.telefono_cliente,cl.direccion_cliente
											FROM factura f
											INNER JOIN empleado v
											ON f.empleado_cod_empleado = v.cod_empleado
											INNER JOIN cliente cl
											ON f.cliente_cod_cliente = cl.cod_cliente
											WHERE ($contabilidad)  AND f.status != 10 AND f.status != 2  LIMIT 30");




		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$no_factura = $factura['cod_factura'];



			$query_productos = mysqli_query($conection,"SELECT f.cod_factura,f.fecha_factura,f.total_factura,f.cliente_cod_cliente,f.status,f.tipo_factura_cod_tipo_factura,f.iva_producto_cod_iva_producto,
																				 cl.empresa_cliente as cliente
																				 from factura f
																				 INNER JOIN cliente cl
																				 ON f.cliente_cod_cliente = cl.cod_cliente
																				 WHERE ($contabilidad) AND f.status != 10 AND f.status != 2
																				 ORDER BY f.cod_factura LIMIT 30");
			$result_detalle = mysqli_num_rows($query_productos);



			$query_productos2 = mysqli_query($conection,"SELECT f.cod_factura,f.fecha_factura,f.total_factura,f.cliente_cod_cliente,f.status,f.tipo_factura_cod_tipo_factura,f.iva_producto_cod_iva_producto,
																				 cl.empresa_cliente as cliente
																				 from factura f
																				 INNER JOIN cliente cl
																				 ON f.cliente_cod_cliente = cl.cod_cliente
																				 WHERE ($contabilidad) AND f.status != 10 AND f.status != 2
																				 ORDER BY f.cod_factura LIMIT 30 OFFSET 30 ");
			$result_detalle2 = mysqli_num_rows($query_productos2);

			ob_start();
		    include(dirname('__FILE__').'/facturaC.php');
		    $html = ob_get_clean();

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->load_Html(utf8_decode($html));
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('factura_'.$contabilidad.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>
