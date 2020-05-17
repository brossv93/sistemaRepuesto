<?php
  include '../conexion.php';
  session_start();
//print_r($_POST);
//exit;
 if (!empty($_POST))
 {

    //extraer datos del productos
    if ($_POST['action'] == 'infoProducto') {

      $producto_id=$_POST['producto'];

      $query= mysqli_query($conection,"SELECT accesorio_id,accesorio_nombre FROM accesorio
                                        WHERE accesorio_id = $producto_id");

      $result= mysqli_num_rows($query);
      if ($result > 0) {

        $data = mysqli_fetch_assoc($query);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
      echo 'error';
      exit;
    }

    //prueba para lo que sería extraer el producto desde venta

    if ($_POST['action'] == 'infoProducto2') {
      $producto_id=$_POST['producto'];
      $query= mysqli_query($conection,"SELECT accesorio_id,accesorio_nombre,accesorio_descripcion,accesorio_precio,accesorio_stock FROM accesorio
                                        WHERE accesorio_nombre = '$producto_id' or accesorio_id = '$producto_id'");
      $result= mysqli_num_rows($query);
      if ($result > 0) {
        $data = mysqli_fetch_assoc($query);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
      echo 'error';
      exit;
    }
    //fin prueba

////prueba de buscar componente


////fin pruba componente

    /////////////Debajo de esto iría agregar productos al stock/////////////

    if ($_POST['action'] == 'addProduct')
    {

     if (!empty($_POST['cantidad']) || !empty($_POST['producto_id']))
      {
          $cantidad = $_POST['cantidad'];
          $cod_producto = $_POST['producto_id'];


            $query_upd=mysqli_query($conection,"CALL actualizar_stock_producto($cantidad,$cod_producto)");
             $result_pro = mysqli_num_rows($query_upd);
             if ($result_pro > 0) {

              $data = mysqli_fetch_assoc($query_upd);
              echo json_encode($data,JSON_UNESCAPED_UNICODE);
                exit;
             }else {
             echo 'error';
           }


      }else
      {
        echo 'error';
      }

    }
  }



    ///////////Fin de agregar productos al stock ////////////



    ////DEBAJO DE ESTO VA LO QUE SERÍA LA PARTE DE FACTURA CREDITO/////

    //extraer datos del productos
    if ($_POST['action'] == 'facturaCredito') {

      $numFactura=$_POST['factura'];

      $query= mysqli_query($conection,"SELECT f.cod_factura,fc.total_factura FROM factura f
                                       INNER JOIN cliente p
                                       ON f.cliente_cod_cliente = p.cod_cliente

                                       INNER JOIN factura_credito fc
                                       ON f.cod_factura = fc.factura_cod_factura
                                       WHERE cod_factura = $numFactura");

      $result= mysqli_num_rows($query);
      if ($result > 0) {

        $data = mysqli_fetch_assoc($query);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
      }
      echo 'error';
      exit;
    }



    //////FIN PARTE DE FACTURA CREDITO///////

    ///agregar productos a entrada
    if ($_POST['action'] == 'pay_factura')
    {

     if (!empty($_POST['cantidad']) || !empty($_POST['factura_cod']))
      {
          $cantidad = $_POST['cantidad'];
          $cod_factura = $_POST['factura_cod'];




          $query_upd = mysqli_query($conection,"CALL pago_factura_credito($cod_factura,$cantidad)");
          $result_pro = mysqli_num_rows($query_upd);
           if ($result_pro > 0)
           {

            $data = mysqli_fetch_assoc($query_upd);


            echo json_encode($data,JSON_UNESCAPED_UNICODE);
              exit;
           }else {
             echo 'error';
           }


      }else
      {
        echo 'error';
      }

    }





    if ($_POST['action'] == 'delProduct')
    {
      ///eliminar productos
      if (empty($_POST['accesorio_id']) || !is_numeric($_POST['accesorio_id']))
      {
        echo "error por que no trae el id de producto";
      }else
      {

        $idProducto = $_POST['accesorio_id'];

          $query_delete = mysqli_query($conection,"DELETE FROM accesorio WHERE accesorio_id = '$idProducto'");
        if ($query_delete)
        {
          echo "ok";
        }else
        {
          echo "error";
          exit;
        }

      }

    }

      //buscar cliente en ventas

      if ($_POST['action'] == 'searchAccesorio')
      {
        if (!empty($_POST['accesorio']))
        {
            $componente = $_POST['accesorio'];

            $query = mysqli_query($conection,"select accesorio_componente from accesorio_componente where accesorio_id LIKE '$accesorio'");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            $data='';
            if ($result > 0)
            {
              $data = mysqli_fetch_assoc($query);
            }else
            {
              $data = 0;
            }
            echo json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        exit;
      }

      //Registrar Clientes en ventas

      if ($_POST['action'] == 'addCliente')
      {
        $ruc = $_POST['ruc_cliente'];
        $nom = $_POST['nom_cliente'];
        $tel = $_POST['tel_cliente'];
        $dir = $_POST['dir_cliente'];

        $query_insert = mysqli_query($conection,"INSERT INTO cliente(ruc_cliente,direccion_cliente,telefono_cliente,empresa_cliente,correo_cliente,contacto_cliente)
																																			VALUES('$ruc','$dir','$tel','$nom','','')");

        if ($query_insert)
        {
              $cod_cliente = mysqli_insert_id($conection);
            	$msg = $cod_cliente;
        }else
        {
              	$msg = 'error';
        }
        mysqli_close($conection);
        echo $msg;
        exit;
      }

      //Agregar producto al detalle temporal
      if ($_POST['action']=='addProductoDetalle')
      {
        if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
          echo "error";
        }else {
          $cod_nombre_producto = $_POST['producto'];
          $cantidad = $_POST['cantidad'];
          $precioFinal = $_POST['precioFinal'];
          $token = $_SESSION['idUser'];

          $query_iva = mysqli_query($conection,"SELECT iva_producto_descripcion FROM iva_producto");
          $result_iva = mysqli_num_rows($query_iva);

          $query_detalle_temp = mysqli_query($conection,"CALL add_detalle_temp('$cod_nombre_producto',$cantidad,'$token',$precioFinal)");
          $result = mysqli_num_rows($query_detalle_temp);

          $detalleTabla = '';
          $sub_total = 0;
          $iva = 0;
          $total = 0;
          $arrayData =array();

          if ($result > 0)
          {
            if ($result_iva > 0)
            {
              $info_va = mysqli_fetch_assoc($query_iva);
              $iva = $info_va['iva_producto_descripcion'];
            }

            while ($data = mysqli_fetch_assoc($query_detalle_temp))
            {
              $precioTotal = round($data['detalle_temp_cantidad']*$data['detalle_temp_precio'],0);
              $sub_total = round($sub_total + $precioTotal,0);
              $total = round($total + $precioTotal,0);

              $detalleTabla .= '<tr>

                                <td colspan="2">'.$data['accesorio_descripcion'].'</td>
                                <td class="textcenter">'.$data['detalle_temp_cantidad'].'</td>
                                <td class="textright">'.$data['detalle_temp_precio'].'</td>
                                <td class="textright">'.$precioTotal.'</td>
                                <td class=""><a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="far fa-trash-alt"></i></a></td>
                               </tr>';
            }

            $impuesto = round($sub_total *($iva/100),0);
            $tl_sniva = round($sub_total - $impuesto,0);
            $total = round($tl_sniva+$impuesto,0);

            $detalle_totales= '<tr>
                                  <td colspan="5" class="textright">SUBTOTAL GS.</td>
                                  <td class="textright">'.$tl_sniva.'</td>
                              </tr>
                              <tr>
                                  <td colspan="5" class="textright"> IVA ('.$iva.'%)</td>
                                  <td class="textright">'.$impuesto.'</td>
                              </tr>
                              <tr>
                                  <td colspan="5" class="textright">TOTAL GS.</td>
                                  <td class="textright">'.$total.'</td>
                              </tr>';

            $arrayData['detalle'] = $detalleTabla;
            $arrayData['totales'] = $detalle_totales;

            echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);

          }else {
            echo 'error';
          }
          mysqli_close($conection);
        }
        exit;
      }


      //extrae datos del detalle tem
      if ($_POST['action']=='searchForDetalle')
      {
        if (empty($_POST['user']))
        {
          echo 'error';
        }else {

          $token = $_SESSION['idUser'];

          $query = mysqli_query($conection,"SELECT tmp.correlativo,tmp.accesorio_id, tmp.token_user,
                                                   tmp.detalle_temp_cantidad,tmp.detalle_temp_precio,
                                                   p.accesorio_id,p.accesorio_descripcion
                                            FROM detalle_temp tmp
                                            INNER JOIN accesorio p
                                            ON tmp.accesorio_id = p.accesorio_id
                                            WHERE token_user = '$token'");
          $result = mysqli_num_rows($query);

          $query_iva = mysqli_query($conection,"SELECT iva_producto_descripcion FROM iva_producto");
          $result_iva = mysqli_num_rows($query_iva);



          $detalleTabla = '';
          $sub_total = 0;
          $iva = 0;
          $total = 0;
          $arrayData =array();

          if ($result >= 0)
          {
            if ($result_iva > 0)
            {
              $info_va = mysqli_fetch_assoc($query_iva);
              $iva = $info_va['iva_producto_descripcion'];
            }

            while ($data = mysqli_fetch_assoc($query))
            {
              $precioTotal = round($data['detalle_temp_cantidad']*$data['detalle_temp_precio'],0);
              $sub_total = round($sub_total + $precioTotal,0);
              $total = round($total + $precioTotal,0);

              $detalleTabla .= '<tr>

                                <td colspan="2">'.$data['accesorio_descripcion'].'</td>
                                <td class="textcenter">'.$data['detalle_temp_cantidad'].'</td>
                                <td class="textright">'.$data['detalle_temp_precio'].'</td>
                                <td class="textright">'.$precioTotal.'</td>
                                <td class=""><a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="far fa-trash-alt"></i></a></td>
                               </tr>';
            }

            $impuesto = round($sub_total *($iva/100),0);
            $tl_sniva = round($sub_total - $impuesto,0);
            $total = round($tl_sniva+$impuesto,0);

            $detalle_totales= '<tr>
                                  <td colspan="5" class="textright">SUBTOTAL GS.</td>
                                  <td class="textright">'.$tl_sniva.'</td>
                              </tr>
                              <tr>
                                  <td colspan="5" class="textright"> IVA ('.$iva.'%)</td>
                                  <td class="textright">'.$impuesto.'</td>
                              </tr>
                              <tr>
                                  <td colspan="5" class="textright">TOTAL GS.</td>
                                  <td class="textright">'.$total.'</td>
                              </tr>';

            $arrayData['detalle'] = $detalleTabla;
            $arrayData['totales'] = $detalle_totales;

            echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);

          }else {
            echo 'error';
          }
          mysqli_close($conection);
        }
        exit;
      }

      //eliminar los datos del detalle temporal
      if ($_POST['action']=='delProductDetalle')
      {
        if (empty($_POST['id_detalle']))
        {
          echo 'error';
        }else {

          $id_detalle = $_POST['id_detalle'];
          $token = $_SESSION['idUser'];



          $query_iva = mysqli_query($conection,"SELECT iva_producto_descripcion FROM iva_producto");
          $result_iva = mysqli_num_rows($query_iva);

          $query_detalle_temp = mysqli_query($conection,"CALL del_detalle_temp($id_detalle,'$token')");
          $result = mysqli_num_rows($query_detalle_temp);

          $detalleTabla = '';
          $sub_total = 0;
          $iva = 0;
          $total = 0;
          $arrayData =array();

          if ($result >= 0)
          {
            if ($result_iva > 0)
            {
              $info_va = mysqli_fetch_assoc($query_iva);
              $iva = $info_va['iva_producto_descripcion'];
            }

            while ($data = mysqli_fetch_assoc($query_detalle_temp))
            {
              $precioTotal = round($data['detalle_temp_cantidad']*$data['detalle_temp_precio'],0);
              $sub_total = round($sub_total + $precioTotal,0);
              $total = round($total + $precioTotal,0);

              $detalleTabla .= '<tr>

                                <td colspan="2">'.$data['accesorio_nombre'].'</td>
                                <td class="textcenter">'.$data['detalle_temp_cantidad'].'</td>
                                <td class="textright">'.$data['detalle_temp_precio'].'</td>
                                <td class="textright">'.$precioTotal.'</td>
                                <td class=""><a class="link_delete" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].');"><i class="far fa-trash-alt"></i></a></td>
                               </tr>';
            }

            $impuesto = round($sub_total *($iva/100),0);
            $tl_sniva = round($sub_total - $impuesto,0);
            $total = round($tl_sniva+$impuesto,0);

            $detalle_totales= '<tr>
                                  <td colspan="5" class="textright">SUBTOTAL GS.</td>
                                  <td class="textright">'.$tl_sniva.'</td>
                              </tr>
                              <tr>
                                  <td colspan="5" class="textright"> IVA ('.$iva.'%)</td>
                                  <td class="textright">'.$impuesto.'</td>
                              </tr>
                              <tr>
                                  <td colspan="5" class="textright">TOTAL GS.</td>
                                  <td class="textright">'.$total.'</td>
                              </tr>';

            $arrayData['detalle'] = $detalleTabla;
            $arrayData['totales'] = $detalle_totales;

            echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);

          }else {
            echo 'error';
          }
          mysqli_close($conection);
        }
        exit;
      }

      //Anular la venta
      if ($_POST['action'] == 'anularVenta')
      {
        $token = md5($_SESSION['idUser']);
        $query_del = mysqli_query($conection,"DELETE FROM detalle_temp WHERE token_user = '$token'");
        mysqli_close($conection);
        if ($query_del)
        {
          echo 'ok';
        }else {
          echo 'error';
        }
      }

      //Facturar la venta
      if ($_POST['action'] == 'procesarVenta')
      {



         $query_iva = mysqli_query($conection,"SELECT iva_producto_id FROM iva_producto");
         $result_iva = mysqli_num_rows($query_iva);
         $iva = mysqli_fetch_assoc($query_iva);
         $iva_prueba = $iva['iva_producto_id'];

         $usuario = $_SESSION['idUser'];


        $query = mysqli_query($conection,"SELECT * FROM detalle_temp WHERE token_user = $usuario");
        $result = mysqli_num_rows($query);

        if ($result > 0)
        {

          $query_procesar = mysqli_query($conection,"CALL procesar_venta($iva_prueba,$usuario)");

          $result_detalle = mysqli_num_rows($query_procesar);


          if ($result_detalle > 0)
          {
            $data = mysqli_fetch_assoc($query_procesar);

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
          }else
          {
            echo 'error';
          }
        }else
        {
          echo 'error';
        }
        mysqli_close($conection);
        exit;
      }
/////detalle de producto

if ($_POST['action'] == 'procesarDetalle')
{


$factura = $_POST['factura'];





    $query_procesar = mysqli_query($conection,"SELECT accesorio_id FROM accesorio WHERE accesorio_id =$factura");

    $result_detalle = mysqli_num_rows($query_procesar);


    if ($result_detalle > 0)
    {
      $data = mysqli_fetch_assoc($query_procesar);

      echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }else
    {
      echo 'error';
    }

  mysqli_close($conection);
  exit;
}


///fin detalle producto
      //info factura
      if ($_POST['action']=='infoFactura')
      {
        if (!empty($_POST['nro_factura']))
        {


        $nro_Factura = $_POST['nro_factura'];

        $query = mysqli_query($conection,"SELECT * FROM venta WHERE venta_id = '$nro_Factura'");
        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0)
        {
          $data = mysqli_fetch_assoc($query);
          echo json_encode($data,JSON_UNESCAPED_UNICODE);
          exit;
        }

        }
        echo "error";
        exit;
     }

     //Anular Factura

     if ($_POST['action'] == 'anularFactura')
     {
       if (!empty($_POST['nro_factura']))
       {

         $nro_Factura =$_POST['nro_factura'];

         $query_anular = mysqli_query($conection,"CALL anular_factura($nro_Factura)");
         $result = mysqli_num_rows($query_anular);

         if ($result > 0)
         {

           $data = mysqli_fetch_assoc($query_anular);
           echo json_encode($data,JSON_UNESCAPED_UNICODE);
           exit;
         }

       }
       echo "error";
       exit;
     }

     //Cambiar contraseña

     if ($_POST['action'] == 'changePassword')
     {
       if (!empty($_POST['passActual']) && !empty($_POST['passNuevo']))
       {
         $password = md5($_POST['passActual']);
         $newPass = md5($_POST['passNuevo']);
         $idUser = $_SESSION['idUser'];

         $code = '';
         $msg = '';
         $arrData = array();

         $query_user = mysqli_query($conection,"SELECT * FROM empleado e
                                                WHERE e.pass_empleado='$password' and e.cod_empleado = $idUser");
         $result = mysqli_num_rows($query_user);
         if ($result>0) {
            $query_upd = mysqli_query($conection,"UPDATE empleado SET pass_empleado = '$newPass' WHERE cod_empleado = $idUser");
            mysqli_close($conection);

            if ($query_upd)
            {
              $code = '00';
              $msg = "Su contraseña se ha actualizado con éxito";
            }else {
              $code = '2';
              $msg = "Su contraseña no se ha actualizado con éxito";
            }
         }else {
           $code = '1';
           $msg = "la contraseña actual es incorrecta";
         }
         $arrData =   array('cod' =>$code ,'msg' =>$msg );
         echo json_encode($arrData,JSON_UNESCAPED_UNICODE);

       }else {
         echo "error";
       };
       exit;
     }

     //actualizar datos empresa

     if ($_POST['action'] == 'updateDataEmpresa') {

       if (empty($_POST['txtRuc']) || empty($_POST['txtNombre']) ||empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['txtDireccion']) || empty($_POST['txtIva']))
       {
         $code = '1';
         $msg = 'Todos los campos son obligatorios';
       }else {
          $intRuc         = intval($_POST['txtRuc']);
          $strNombre      = $_POST['txtNombre'];
          $strRazonSocial = $_POST['txtRazonSocial'];
          $intTelefono    = intval($_POST['txtTelefono']);
          $strEmail       = $_POST['txtEmail'];
          $strDireccion   = $_POST['txtDireccion'];
          $intIva         = $_POST['txtIva'];

          $query_update = mysqli_query($conection,"UPDATE configuracion SET ruc_configuracion = $intRuc,
                                                                            nombre_configuracion= '$strNombre',
                                                                            razon_social_configuracion = '$strRazonSocial',
                                                                            telefono_configuracion = $intTelefono,
                                                                            email_configuracion = '$strEmail',
                                                                            direccion_configuracion='$strDireccion',
                                                                            iva_configuracion = '$intIva'
                                                                            WHERE cod_configuracion = 1");
         mysqli_close($conection);
         if ($query_update)
         {
           $code = '00';
           $msg = "Datos actulizados correctamente";
         }else {
           $code = '2';
           $msg = "Error al actualizar los datos";
         }
       }
       $arrData = array('cod'=>$code,'msg' => $msg);
       echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
       exit;
    }




  exit;
?>
