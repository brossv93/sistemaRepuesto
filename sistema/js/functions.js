$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------

    //////Modal form add producto
    $('.add_product').click(function(e)
    {
        e.preventDefault();
        var producto = $(this).attr('product');//este product es el nombre de donde extrae el cod producto
        var action = 'infoProducto';
        $.ajax
        ({
            url:'ajax.php',
            type:'POST',
            async: true,
            data:{action:action,producto:producto},

            success: function(response){

              if (response != 'error') {
                var info = JSON.parse(response);

              //$('#producto_id').val(info.cod_producto);
                //$('.nameProducto').html(info.cod_nombre_producto);


                $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                                    '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br>Agregar Producto</h1>'+
                                    '<h2 class="nameProducto">'+info.accesorio_nombre+'</h2><br>'+
                                    '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad a ingresar" required>'+
                                    '<input type="hidden" name="producto_id" id="producto_id" value="'+info.accesorio_id+'" required>'+
                                    '<input type="hidden" name="action" value="addProduct" required>'+
                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Agregar Stock</button>'+
                                    '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                    '</form>');
              }
            },
            error: function(error){
              console.log(error);
            }

        });


        $('.modal').fadeIn();
    });

    //modal form delete product

    $('.del_product').click(function(e)
    {
        e.preventDefault();
        var producto = $(this).attr('product');//este product es el nombre de donde extrae el cod producto
        var action = 'infoProducto';
        $.ajax
        ({
            url:'ajax.php',
            type:'POST',
            async: true,
            data:{action:action,producto:producto},

            success: function(response){

              if (response != 'error') {
                var info= JSON.parse(response);

              //  $('#producto_id').val(info.cod_producto);
                //$('.nameProducto').html(info.cod_nombre_producto);

                $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
                                    '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br>Eliminar Producto</h1>'+
                                    '<p>¿Esta seguro de eliminar el siguiente registro?</p>'+
                                    '<h2 class="nameProducto">'+info.accesorio_nombre+'</h2><br>'+
                                    '<input type="hidden" name="accesorio_id" id="accesorio_id" value="'+info.accesorio_id+'" required>'+
                                    '<input type="hidden" name="action" value="delProduct" required>'+
                                    '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fas fa-minus-circle"></i>Cerrar</a>'+
                                    '<button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>'+
                                    '<div class="alert alertAddProduct"></div>'+
                                    '</form>');
              }
            },
            error: function(error){
              console.log(error);
            }

        });


        $('.modal').fadeIn();
    });


    ///////debajo de esto voy a hacer la prueba para armar el modal que tiene que funcionar para las facturas creditos/////

    //////Modal form descuento factura credito
    $('.pay_factura').click(function(e)
    {
        e.preventDefault();
        var factura = $(this).attr('nFactura');//este product es el nombre de donde extrae el cod producto y viene del boton que estan en ventas, en la parte de acciones
        var action = 'facturaCredito';
        $.ajax
        ({
            url:'ajax.php',
            type:'POST',
            async: true,
            data:{action:action,factura:factura},

            success: function(response){

              if (response != 'error') {
                var info = JSON.parse(response);

              //$('#producto_id').val(info.cod_producto);
                //$('.nameProducto').html(info.cod_nombre_producto);


                $('.bodyModal').html('<form action="" method="post" name="form_desc_fact_credi" id="form_desc_fact_cred" onsubmit="event.preventDefault(); sendDataFactCredi();">'+
                                    '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br>Agregar Pago</h1>'+
                                    '<h2 class="codFactura">'+"Numero de Factura: "+info.cod_factura+'</h2><br>'+
                                    '<h2 class="codFactura">'+"Deuda Actual"+'</h2><br>'+
                                    '<h2 class="codFactura">'+info.total_factura+'</h2><br>'+
                                    '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad a pagar" required>'+
                                    '<input type="hidden" name="factura_cod" id="factura_cod" value="'+info.cod_factura+'" required>'+
                                    '<input type="hidden" name="action" value="pay_factura" required>'+
                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button type="submit" class="btn_new"><i class="fas fa-plus"></i> Procesar</button>'+
                                    '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                    '</form>');
              }
            },
            error: function(error){
              console.log(error);
            }

        });


        $('.modal').fadeIn();
    });

    /////////////fin de la prueba del modal que elimina factura credito//////////////////////
    //Activa campos para registrar clientes
    $('.btn_new_cliente').click(function(e)
    {

      e.preventDefault();

      $('#nom_cliente').removeAttr('disabled');
      $('#tel_cliente').removeAttr('disabled');
      $('#dir_cliente').removeAttr('disabled');

      $('#div_registro_cliente').slideDown();
    });

    //Buscar clientes en la parte de facturacion

    $('#ruc_cliente').keyup(function(e)
    {
      e.preventDefault();

      var cl=$(this).val();
      var action = 'searchCliente';

      $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: {action:action,cliente:cl},
        success: function(response)
        {

            if (response == 0)
            {
                //Agregar id a input hiden
                $('#idCliente').val('');
                $('#nom_cliente').val('');
                $('#tel_cliente').val('');
                $('#dir_cliente').val('');

                //Mostrar Boton Agregar
                $('.btn_new_cliente').slideDown();
              }else
              {
                  var data = $.parseJSON(response);
                  $('#idCliente').val(data.cod_cliente);
                  $('#nom_cliente').val(data.empresa_cliente);
                  $('#tel_cliente').val(data.telefono_cliente);
                  $('#dir_cliente').val(data.direccion_cliente);

                  //ocultar boton Agregar
                  $('.btn_new_cliente').slideUp();

                  //Bloquear campos
                  $('#nom_cliente').attr('disabled','disabled');
                  $('#tel_cliente').attr('disabled','disabled');
                  $('#dir_cliente').attr('disabled','disabled');

                  //Oculta Boton Agregar
                  $('.div_registro_cliente').slideUp();
              }

            },
          error:function(error)
          {

          }
      });

    });


    //crear cliente desde ventas

    $('#form_new_cliente_venta').submit(function(e)
    {
        e.preventDefault();

        $.ajax({
          url:'ajax.php',
          type:'POST',
          async: true,
          data: $('#form_new_cliente_venta').serialize(),
          success: function(response)
          {

              if (response != 'error')
              {
                  //Agregar id a input hiden
                  $('#idCliente').val(response);
                  //Bloquea los campos una vez que creamos el cliente desde ventas
                  $('#nom_cliente').attr('disabled','disabled');
                  $('#tel_cliente').attr('disabled','disabled');
                  $('#dir_cliente').attr('disabled','disabled');

                  //Oculta Boton Agregar
                  $('.btn_new_cliente').slideUp();
                  //Oculta el boton Guardar
                  $('#div_registro_cliente').slideUp();
              }



          },
            error:function(error)
            {

            }
        });
    });

    //buscar el producto en venta

    $('#txt_cod_producto').keyup(function(e)
    {
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProducto2';
        if(producto !='')
      {
        $.ajax({
          url:'ajax.php',
          type:'POST',
          async: true,
          data: {action:action,producto:producto},

          success: function(response)

          {

            if (response != 'error') {

              var info = JSON.parse(response);
              $('#txt_descripcion').html(info.accesorio_descripcion);
              $('#txt_existencia').html(info.accesorio_stock);
              $('#txt_precio').val(info.accesorio_precio);
              $('#txt_precio_total').html(info.accesorio_precio);

              //activar cantidad_entrada
              $('#txt_cant_producto').removeAttr('disabled');
              $('#txt_precio').removeAttr('disabled');

              //Mostrar botón Agregar
              $('#add_product_venta').slideDown();
            }else
            {
              $('#txt_descripcion').html('-');
              $('#txt_existencia').html('-');
              $('#txt_precio').val('-');
              $('#txt_precio_total').html('-');

              //activar cantidad_entrada
              $('#txt_cant_producto').attr('disabled','disabled');
                $('#txt_precio').attr('disabled','disabled');

              //Mostrar botón Agregar
              $('#add_product_venta').slideUp();
            }

          },
            error:function(error)
            {

            }
        });
      }
    });

    //Validar cantidad del producto antes de Agregar
    $('#txt_cant_producto').keyup(function(e)
    {
      e.preventDefault();
      var precio_total = $(this).val() * $('#txt_precio').val();
      var existencia = parseInt($('#txt_existencia').html());

      $('#txt_precio_total').html(precio_total);

      //ocultar el boton agregar si la cantidad es menor que function
      if ( ($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia))

      {
          $('#add_product_venta').slideUp();
      }else
      {
          $('#add_product_venta').slideDown();
      }

    });

    //agregar producto al detalle_venta
    $('#add_product_venta').click(function(e)
    {
      e.preventDefault();
      if ($('#txt_cant_producto').val() > 0)
      {
          var cod_nombre_producto = $('#txt_cod_producto').val();
          var cantidad = $('#txt_cant_producto').val();
          var precioFinal = $('#txt_precio').val();
          var action = 'addProductoDetalle';

          $.ajax({
            url : 'ajax.php',
            type: "POST",
            async: true,
            data :{action:action,producto:cod_nombre_producto,cantidad:cantidad,precioFinal:precioFinal},

            success: function(response)
            {
              if (response != 'error')
              {
                  var info = JSON.parse(response);
                  $('#detalle_venta').html(info.detalle);
                  $('#detalle_totales').html(info.totales);

                  $('#txt_cod_producto').val('');
                  $('#txt_descripcion').html('-');
                  $('#txt_existencia').html('-');
                  $('#txt_cant_producto').val('0');
                  $('#txt_precio').val('0');
                  $('#txt_precio_total').html('0.00');

                  //Bloquemos el campo cantidad despues de agregar el producto al detalle
                  $('#txt_cant_producto').attr('disabled','disabled');
                    $('#txt_precio').attr('disabled','disabled');
                  //Ocultamos el boton agregar
                  $('#add_product_venta').slideUp();

              }else
              {
                  console.log('Error al insertar el producto');
              }
              viewProcesar();
            },
            error:function()
            {

            }

      });
    }
  });

  //Anular Venta
  $('#btn_anular_venta').click(function(e)
   {
      e.preventDefault();

      var rows = $('#detalle_venta tr').length;
      if (rows > 0)
      {
          var action = 'anularVenta';

          $.ajax
          ({
              url  : 'ajax.php',
              type : "POST",
              async: true,
              data : {action:action},

              success: function(response)
              {
                console.log(response);
                if (response != 'error')
                {
                    location.reload();
                }
              },
              error:function(error){

              }
          });
      }
   });

   //Procesar Venta
   $('#btn_facturar_venta').click(function(e)
    {
       e.preventDefault();

       var rows = $('#detalle_venta tr').length;
       if (rows > 0)
       {
           var action = 'procesarVenta';


           $.ajax
           ({
               url  : 'ajax.php',
               type : "POST",
               async: true,
               data : {action:action},

               success: function(response)
               {

                 if (response != 'error')
                 {
                   var info = JSON.parse(response);


                   generarPDF(info.venta_id);

                   location.reload();
                 }else
                 {
                     console.log('error al cargar la factura');
                 }
               },
               error:function(error){

               }
           });
       }
    });


    ///prueba de generar detalle productos

    $('#btn_detalle_producto').click(function(e)
     {
        e.preventDefault();


            var action = 'procesarDetalle';
            var factura = $(this).attr('f');//este fac extra del boton anular factura

            $.ajax
            ({
                url  : 'ajax.php',
                type : "POST",
                async: true,
                data : {action:action,factura:factura},

                success: function(response)
                {

                  if (response != 'error')
                  {
                    var info = JSON.parse(response);


                    generarPDF2(info.accesorio_id);

                    location.reload();
                  }else
                  {
                      console.log('error al cargar la factura acá');
                  }
                },
                error:function(error){
                  console.log('error al cargar la factura2');
                }
            });

     });

    //fin detalle producto

    //abajo esta la funcion modal anular factura

    $('.anular_factura').click(function(e)
    {
        e.preventDefault();
        var nro_factura = $(this).attr('fac');//este fac extra del boton anular factura
        var action = 'anularFactura';
        $.ajax
        ({
            url:'ajax.php',
            type:'POST',
            async: true,
            data:{action:action,nro_factura:nro_factura},

            success: function(response){
              location.reload();
              if (response != 'error') {

              }
            },
            error: function(error){
              console.log(error);
            }

        });



    });

    //Ver factura
    $('.view_factura').click(function(e){

        e.preventDefault();

        var venta_id = $(this).attr('f');

        generarPDF(venta_id);

    });

    //////////abajo de este hago la prueba de contabilidad

    //ver producto

    //Ver factura
    $('.view_factura2').click(function(e){

        e.preventDefault();

        var accesorio_id = $(this).attr('f');

        generarPDF2(accesorio_id);

    });

    //fin de ver producto

    //Ver factura
    $('.view_contabilidad').click(function(e){

        e.preventDefault();


        var variable_contabilidad = $(this).attr('f');

        generarPDFContabilidad(variable_contabilidad);

    });

    $('.view_contabilidadA').click(function(e){

        e.preventDefault();


        var variable_contabilidadA = $(this).attr('f');

        generarPDFContabilidadA(variable_contabilidadA);

    });

    //////fin de contabilidad

    ///Cambiar pass
    $('.newPass').keyup(function(){
      validPass();
    });

    //Form Cambiar contraseña
    $('#frmChangePass').submit(function(e){
      e.preventDefault();

      var passActual = $('#txtPassUser').val();
      var passNuevo = $('#txtNewPassUser').val();
      var confirmPassNuevo = $('#txtPassConfirm').val();
      var action = "changePassword";

      if (passNuevo != confirmPassNuevo)
      {
          $('.alertChangePass').html('<p style="color:red;">las contraseñas no coinciden</p>');
          $('.alertChangePass').slideDown();
          return false;
      }
      $.ajax
      ({
          url  : 'ajax.php',
          type : "POST",
          async: true,
          data : {action:action,passActual:passActual,passNuevo:passNuevo},

          success: function(response)
          {
            if (response != 'error')
            {
                var info = JSON.parse(response);
                if (info.cod=='00')
                {
                    $('.alertChangePass').html('<p style="color:white;">'+info.msg+'</p>');
                    $('#frmChangePass')[0].reset();
                }else {
                  $('.alertChangePass').html('<p style="color:red;">'+info.msg+'</p>');

                }
                $('.alertChangePass').slideDown();
            }
          },
          error:function(error){

          }
      });
    });


    //actualizar datos de nuestra empresa
    $('#frmEmpresa').submit(function(e){
      e.preventDefault();
      var intRuc         = $('#txtRuc').val();
      var strNombre      = $('#txtNombre').val();
      var strRazonSocial = $('#txtRazonSocial').val();
      var intTelefono    = $('#txtTelefono').val();
      var strEmail       = $('#txtEmail').val();
      var strDireccion   = $('#txtDireccion').val();
      var intIva         = $('#txtIva').val();

      if (intRuc == '' || strNombre == '' || intTelefono == '' || strEmail == '' || strDireccion == '' || intIva =='')
      {
          $('.alertFormEmpresa').html('<p style="color:red;"></p>');
          $('alertFormEmpresa').slideDown();
          return false;
      }

      $.ajax
      ({
          url  : 'ajax.php',
          type : "POST",
          async: true,
          data :$('#frmEmpresa').serialize(),
          beforeSend: function(){
              $('.alertFormEmpresa').slideUp();
              $('.alertFormEmpresa').html('');
              $('#frmEmpresa input').attr('disabled','disabled');
          },
          success: function(response)
          {


                var info = JSON.parse(response);
                if (info.cod == '00')
                {
                    $('.alertFormEmpresa').html('<p style="color: #23922d;">'+info.msg+'</p>');

                    $('.alertFormEmpresa').slideDown();
                }else {
                    $('.alertChangePass').html('<p style="color:red;">'+info.msg+'</p>');
                }
                $('.alertFormEmpresa').slideDown();
                $('#frmEmpresa input').removeAttr('disabled');

          },
          error: function(error){

          }


      });
    });

});//End Readi

function validPass()
{
  var passNuevo = $('#txtNewPassUser').val();
  var confirmPassNuevo = $('#txtPassConfirm').val();
  if (passNuevo != confirmPassNuevo)
  {
      $('.alertChangePass').html('<p style="color:red;">las contraseñas no coinciden</p>');
      $('.alertChangePass').slideDown();
      return false;
  }

  $('.alertChangePass').html('');
  $('.alertChangePass').slideDown();
}

function anularFactura()
{
    var nro_factura = $('#nro_factura').val();
    var action = 'anularFactura';

    $.ajax({

      url: 'ajax.php',
      type: "POST",
      async: true,
      data:{action:action,nro_factura:nro_factura},

      success:function(response)
      {

        if (response == 'error')
        {
            $('.alertAddProduct').html('<p style="color:red;">Error al anular factura</p>');
        }else
        {

            $('#row_'+nro_factura+' .estado').html('<span class = "anulada"> Anulado </span>');
            $('#form_anular_factura .btn_ok').remove();
            $('#row_'+nro_factura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
            $('.alertAddProduct').html('<p>Factura Anulada</p>');

        }
      },
      error: function(error){

      }

    });
}

//funcion que arma el page_pdf

function generarPDF(factura)
{
  var ancho = 350;
  var alto  = 200;
  //Calcular posicion x,y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho/2));
  var y = parseInt((window.screen.height/2)- (ancho/2));

  $url = 'factura/generaFactura.php?f='+factura;
  window.open($url,"Factura","left="+x+",top="+y+"height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

//abajo eliminamos los detalles

function generarPDF2(factura)
{
  var ancho = 250;
  var alto  = 100;
  //Calcular posicion x,y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho/2));
  var y = parseInt((window.screen.height/2)- (ancho/2));

  $url = 'factura/generaFactura2.php?f='+factura;
  window.open($url,"Factura","left="+x+",top="+y+"height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

///////////abajo esta la funcion de contabilidad


function generarPDFContabilidad(Contabilidad)
{
  var ancho = 1000;
  var alto  = 800;
  //Calcular posicion x,y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho/2));
  var y = parseInt((window.screen.height/2)- (ancho/2));

  $url = 'factura/generaFacturaC.php?f='+Contabilidad;
  window.open($url,"Factura","left="+x+",top="+y+"height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

function generarPDFContabilidadA(Contabilidad)
{
  var ancho = 1000;
  var alto  = 800;
  //Calcular posicion x,y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho/2));
  var y = parseInt((window.screen.height/2)- (ancho/2));

  $url = 'factura/generaFacturaA.php?f='+Contabilidad;
  window.open($url,"Factura","left="+x+",top="+y+"height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}
///fin de la parte de contabilidad
function del_product_detalle(correlativo)
{
  var action = 'delProductDetalle';
  var id_detalle = correlativo;

  $.ajax({
    url : 'ajax.php',
    type: "POST",
    async: true,
    data :{action:action,id_detalle:id_detalle},

    success: function(response)
    {
      if (response != 'error')
      {
        if (response != 'error')
        {
          var info = JSON.parse(response);

          $('#detalle_venta').html(info.detalle);
          $('#detalle_totales').html(info.totales);

          $('#txt_cod_producto').val('');
          $('#txt_descripcion').html('-');
          $('#txt_existencia').html('-');
          $('#txt_cant_producto').val('0');
          $('#txt_precio').html('0');
          $('#txt_precio_total').html('0.00');

          //Bloquemos el campo cantidad despues de agregar el producto al detalle
          $('#txt_cant_producto').attr('disabled','disabled');

          //Ocultamos el boton agregar
          $('#add_product_venta').slideUp();



        }else
        {
            $('#detalle_venta').html('');
            $('#detalle_totales').html('');
        }

      }else
      {
          console.log('Error en esta parte que sería la de eliminar el detalle');
      }
      viewProcesar();
    },
    error:function(error)
    {

    }

});
}

//Mostrar ocultar boton detalle

function viewProcesar()
{
  if ($('#detalle_venta tr').length > 0)
  {
      $('#btn_facturar_venta').show();
  }else
  {
      $('#btn_facturar_venta').hide();
  }
}

//la funcion de abajo hace que se mantengan los detalles hasta terminar el proceso
function searchForDetalle(id)
{
  var action = 'searchForDetalle';
  var user = id;

  $.ajax({
    url : 'ajax.php',
    type: "POST",
    async: true,
    data :{action:action,user:user},

    success: function(response)
    {
      if (response != 'error')
      {
          var info = JSON.parse(response);
          $('#detalle_venta').html(info.detalle);
          $('#detalle_totales').html(info.totales);

      }else
      {
          console.log('Error al intentar mantener los productos');
      }
      viewProcesar();
    },
    error:function(error)
    {

    }

});
}

//////lo de abajo es lo que procesa la factura credito/////////

function sendDataFactCredi(){
  $('.alertAddProduct').html('');

  $.ajax
  ({
      url:'ajax.php',
      type:'POST',
      async: true,
      data: $('#form_desc_fact_cred').serialize(),

      success: function(response){

        if (response== 'error')
        {
          $('.alertAddProduct').html('<p style="color:red;">Error Insertar el pago.</p>');
        }else
        {
         var info = JSON.parse(response);

        //$('.row'+info.codigo+' .celStock').html(info.nueva_existencia);
      //  if (info.nueva_venta = 0)
      //  {
      //    $('.row'+info.nroFactura+' .estado').html('1');
      //  }
         location.reload(0);
         $('#txtCantidad').val('');
         $('.alertAddProduct').html('<p>Stock Actualizado.</p>');
        }

      },

      error: function(error){
        console.log(error);
      }

  });


}

////////fin de lo que procesa factura credito////////////
//fin de esa funcion
function sendDataProduct(){
  $('.alertAddProduct').html('');

  $.ajax
  ({
      url:'ajax.php',
      type:'POST',
      async: true,
      data: $('#form_add_product').serialize(),

      success: function(response){
        console.log(response);
        if (response== 'error')
        {
          $('.alertAddProduct').html('<p style="color:red;">Error al Agregar Stock.</p>');
        }else
        {
         var info = JSON.parse(response);

         $('.row'+info.codigo+' .celStock').html(info.nueva_existencia);
         $('#txtCantidad').val('');
         $('.alertAddProduct').html('<p>Stock Actualizado.</p>');
        }

      },

      error: function(error){
        console.log(error);
      }

  });


}
////Elimar producto
function delProduct(){
  var pr =  $('#producto_id').val();
  $('.alertAddProduct').html('');

  $.ajax
  ({
      url:'ajax.php',
      type:'POST',
      async: true,
      data: $('#form_del_product').serialize(),

      success: function(response){


        if (response== 'error')
        {
          $('.alertAddProduct').html('<p style="color:red;">Error al eliminar producto.</p>');
        }else
        {


         $('.row'+pr).remove();
         $('#form_del_product .btn_ok').remove();
         $('.alertAddProduct').html('<p>Producto Eliminado.</p>');
       }

      },

      error: function(error){
        console.log(error);
      }

  });


}
function closeModal(){
  $('.alertAddProduct').html('');
  $('#txtCantidad').val('');
  $('.modal').fadeOut();
}
