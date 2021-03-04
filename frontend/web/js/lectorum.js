var tipo_envio = '';
var precio_envio = '';
var descuento_precio = '';
var pago = '';
var guia = '';
var forma_de_pagar = '';
var unootres = '';
var debocred = '';
var fecha_normal = '';
var timestamp = '';
var cupon_id = '';

if ($('#baseurl').val() == 'http://www.uppl.blackrobot.mx'){
    document.domain = 'blackrobot.mx';
} else if ($('#baseurl').val() != 'http://localhost/uppl/frontend/web'){
    document.domain = 'unpaseoporloslibros.com';    
}

$('.desplegar-menu').on('click', function(){
    $(this).toggleClass('desplegado');
    if(!$(this).hasClass('desplegado')){
        $('body').removeClass('no-scroll');
        $('#menu-mobile').fadeOut('fast');
    }else{
        $('body').addClass('no-scroll');
        $('#menu-mobile').fadeIn('fast');
    }

});

setInterval(function(){
    var num_rand = Math.floor(Math.random() * 100) + 1 ;
    $("#nom_cliente_compra").text(nombre_consumidor[num_rand]);
    $("#lugar_compra").text(estados_compra[num_rand]);
    $("#producto_compra").text(nombre_libro_compra[num_rand]);
    $("#tiempo_compra").text(horas_compra[num_rand]);
    $("#ap_cliente_compra").text(apellidos_consumidor[num_rand]);
    $("#com_id_src").attr("href", "https://www.lectorum.com.mx/site/ver?id="+id_libro_compra[num_rand]);
    $("#imagen_prod_compra").attr("src", "https://www.lectorum.com.mx/images/portadas/"+imagen_libro_compra[num_rand]);
}, 30000);

//setInterval(function(){ 
//    $(".custom-social-proof").stop().slideToggle('slow');
//}, 15000);
//
//$(".custom-close").click(function() {
//    $(".custom-social-proof").stop().slideToggle('slow');
//});

$('.cerrar-menu').on('click', function(){
    $('.desplegar-menu').toggleClass('desplegado');
    if(!$('.desplegar-menu').hasClass('desplegado')){
        $('body').removeClass('no-scroll');
        $('#menu-mobile').fadeOut('fast', function(){
            $('#encabezado-principal').toggleClass('oculto');
            document.getElementById('bus_main').style.display = "block";
            $('#navbar-mov').height('auto');
            $('#navbar-mov').removeClass('colorMenu');
            $('#navbar-mov').addClass('color');
        });
    }else{
        $('body').addClass('no-scroll');
        $('#menu-mobile').fadeIn('fast', function(){
            $('#encabezado-principal').toggleClass('oculto');
            document.getElementById('bus_main').style.display = "none";
            $('#navbar-mov').height('12%');
            $('#navbar-mov').removeClass('color');
            $('#navbar-mov').addClass('colorMenu');
        });
    }
});

$('.desplegar-menu1').on('click', function(){
        $('.desplegar-menu').toggleClass('desplegado');
        if(!$('.desplegar-menu').hasClass('desplegado')){
            $('body').removeClass('no-scroll');
            $('#menu-mobile').fadeOut('fast', function(){
                $('#encabezado-principal').toggleClass('oculto');
                document.getElementById('bus_main').style.display = "block";
                $('#navbar-mov').height('auto');
                $('#navbar-mov').removeClass('colorMenu');
                $('#navbar-mov').addClass('color');
            });
        }else{
            $('body').addClass('no-scroll');
            $('#menu-mobile').fadeIn('fast', function(){
                $('#encabezado-principal').toggleClass('oculto');
                document.getElementById('bus_main').style.display = "none";
                $('#navbar-mov').height('12%');
                $('#navbar-mov').removeClass('color');
                $('#navbar-mov').addClass('colorMenu');
            });
        }
    });

$('#iconos, .close-log, .ini-s').on('click', function(){
    $('.login-user').toggleClass('desplegado');
    if(!$('.login-user').hasClass('desplegado')){
        $('#navbar').toggleClass('color-log');
        $('body').removeClass('no-scroll');
        $('.login-user').fadeOut('fast', function(){
            $('.login-user').toggleClass('oculto');
        });
    }else{
        $('#navbar').toggleClass('color-log');
        $('body').addClass('no-scroll');
        $('#menu-mobile').fadeOut('fast', function(){
            $('#encabezado-principal').toggleClass('oculto');
            document.getElementById('bus_main').style.display = "block";
            $('#navbar-mov').height('auto');
            $('#navbar-mov').removeClass('colorMenu');
            $('#navbar-mov').addClass('color');
            $('.desplegar-menu').removeClass('desplegado');
        });
        $('.login-user').fadeIn('fast', function(){
            $('.login-user').toggleClass('oculto');
        });
    }
});

$('.btn-realizar-pago').click(function(e){ 
    e.preventDefault();
    e.stopImmediatePropagation();
    if($('#check-terminos').is(':checked')){
        $('.btn-realizar-pago').addClass('btn-disabled');
        $('.btn-realizar-pago').attr('disabled', true);
        $('.img-procesando').show();

        var editoriales = {};
        
        $('.compra-cantidad').each(function(){
            if(editoriales[$(this).data('editclave')]){ 
                editoriales[$(this).data('editclave')] += $(this).data('subtotal');
            } else {
                editoriales[$(this).data('editclave')] = $(this).data('subtotal');
            } 
        });
        
        var nombre = $('#checkout-nombre').val()+' '+$('#checkout-apellido').val();
        var numero = $('#tarjeta-num').val();
        var codigo = $('#tarjeta-codigo').val();
        var mes = $('#tarjeta-vencimiento').val().slice(0, -3);
        var anio = $('#tarjeta-vencimiento').val().slice(3);

        var telefono = $('#checkout-telefono').val().replace(/ /g, "");
        var email = $('#checkout-correo').val();
        var calle = $('#checkout-calle').val();
        var cp = $('#checkout-cp').val();

        var cupon = $('#cupon-id').val();
        var porcentaje = $('#cupon-porcentaje').val();
        var descuento = $('#cupon-descuento').val();

        var carrito = $('.compra-cantidad').data('carrito');
        
        var baseurl = $('#baseurl').val();
        
        var editorial_push = {};
        
        $.each(editoriales, function(clave, subtotal){
            var editorial_id = $('#compra-editorial-'+clave+'').data('editorial');
            var ruta = $('#compra-editorial-'+clave+'').data('ruta');
            if (!$('#frame-'+clave.toLowerCase()).length){

                var ifrm = document.createElement("iframe");
    //            ifrm.setAttribute("src", ""+baseurl+"/pagos/pagar?subtotal="+subtotal+"");
                ifrm.setAttribute("src", ruta+"/pagos/pagar?subtotal="+subtotal+"");
                ifrm.setAttribute("id", 'frame-'+clave.toLowerCase());
                ifrm.style.width = "1px";
                ifrm.style.height = "1px";
                document.body.appendChild(ifrm);
            };
            setTimeout(function(){
                console.log($('#frame-'+clave.toLowerCase()+'').contents().find('#copy-nombre').length);
                console.log(nombre);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-nombre').val(nombre);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-tarjeta').val(numero);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-mes').val(mes);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-anio').val(anio);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-seguridad').val(codigo);

                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-telefono').val(telefono);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-email').val(email);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-calle').val(calle);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-cp').val(cp);

                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-cupon').val(cupon);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-descuento').val(descuento);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-porcentaje').val(porcentaje);

                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-carrito').val(carrito);
                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-editorial-id').val(editorial_id);
    //                $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-'+clave+'-id').val(editorial_id);

                $('#frame-'+clave.toLowerCase()+'').contents().find('.prueba-alerta').trigger('click');

                setTimeout(function(){
                    var editorial = {};

                    editorial['exito'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-exito').val();
                    editorial['order'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-order').val();
                    editorial['monto'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-monto').val();
                    editorial['codigo'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-codigo').val();
                    editorial['numeros'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-numeros').val();
                    editorial['marca'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-marca').val();
                    editorial['tipo'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-tipo').val();
                    editorial['id'] = $('#frame-'+clave.toLowerCase()+'').contents().find('#copy-editorial-id').val();

                    editorial_push[clave] = (editorial);
                }, 7000*Object.keys(editoriales).length)
            }, 3000)
        });        
        
        setTimeout(function(){
            
            var llave_publica = $('#conk_key').val();

            var numero = $('#tarjeta-num').val();
            var codigo = $('#tarjeta-codigo').val();
            var mes = $('#tarjeta-vencimiento').val().slice(0, -3);
            var anio = $('#tarjeta-vencimiento').val().slice(3);
            if(!Conekta.card.validateNumber(numero)){
                alert('no valido num de tarjeta')
                $('.error-carrito.payment').removeClass('oculto');
                $('.error-carrito.payment').addClass('mensaje-error');
                $('.error-carrito.payment').html("Número de tarjeta inválido");
                $("#continuar-pago-payment").prop('disabled',false);
                $('#realizar-pago').addClass('btnpago-activo');
                $('#realizar-pago').removeClass('btnpago-check');
                $('#loadergif-pago').addClass('oculto');
                $('.img-procesando').hide();
                return;
            }
            
            if (Conekta.card.getBrand(numero) == 'amex'){
                alert('No se aceptan pagos con tarjetas American Express')
                $('.error-carrito.payment').removeClass('oculto');
                $('.error-carrito.payment').addClass('mensaje-error');
                $('.error-carrito.payment').html("No se aceptan pagos con tarjetas American Express");
                $("#continuar-pago-payment").prop('disabled',false);
                $('#realizar-pago').addClass('btnpago-activo');
                $('#realizar-pago').removeClass('btnpago-check');
                $('#loadergif-pago').addClass('oculto');
                $('.img-procesando').hide();
                return;                
            }
            if(!Conekta.card.validateCVC(codigo)){
                alert('no valido cvv')
                $('.error-carrito.payment').removeClass('oculto');
                $('.error-carrito.payment').addClass('mensaje-error');
                $('.error-carrito.payment').html("CVC inválido");
                $("#continuar-pago-payment").prop('disabled',false);
                $('#realizar-pago').addClass('btnpago-activo');
                $('#realizar-pago').removeClass('btnpago-check');
                $('#loadergif-pago').addClass('oculto');
                $('.img-procesando').hide();
                return;
            }
            if(!Conekta.card.validateExpirationDate(mes,anio)){
                alert('no valido mes o año')
                $('.error-carrito.payment').removeClass('oculto');
                $('.error-carrito.payment').addClass('mensaje-error');
                $('.error-carrito.payment').html("Fecha de expiración inválida");
                $("#continuar-pago-payment").prop('disabled',false);
                $('#realizar-pago').addClass('btnpago-activo');
                $('#realizar-pago').removeClass('btnpago-check');
                $('#loadergif-pago').addClass('oculto');
                $('.img-procesando').hide();
                return;
            }
            Conekta.setPublicKey(llave_publica);
            var tokenParams = {
                "card": {
                    "number": numero,
                    "name": $('#checkout-nombre').val()+' '+$('#checkout-apellido'),
                    "exp_year": anio,
                    "exp_month": mes,
                    "cvc": codigo
                }
            };
            Conekta.Token.create(tokenParams, function(token) {
    //            var form = $('#compra-form');
                var data = [];
                var envio = {};
                var cupon = {};
                var pago = {};
    //            var editoriales = {};
                envio["nombre"] = $('#checkout-nombre').val();
                envio["apellidos"] = $('#checkout-apellido').val();
                envio["telefono"] = $('#checkout-telefono').val().replace(/ /g, "");
                envio["calle"] = $('#checkout-calle').val();
    //            envio["num_ext"] = form.find('#num_ext_envio').val();
    //            envio["num_int"] = form.find('#num_int_envio').val();
                envio["ciudad"] = $('#checkout-ciudad').val();
                envio["pais"] = $('#pais-id option:selected').val();
                envio["estado"] = $('#estado-id option:selected').val();
                envio["cp"] = $('#checkout-cp').val();
    //            envio["delegacion"] = form.find('#delegacion_envio').val();
                envio["colonia"] = $('#checkout-colonia').val();
                envio["emailtext"] = $('#checkout-correo').val();
                envio["tipo"] = $('input:radio[name=radio-envio]:checked').val();
                envio["costo"] = $('#compra-envio').val();

                cupon["id"] = $('#cupon-id').val();
                cupon["porcentaje"] = $('#cupon-porcentaje').val();
                cupon["descuento"] = $('#cupon-descuento').val();

                pago["tipo"] = $('input:radio[name=radio-pago]:checked').val();
                pago["total"] = $('#compra-total').val();
    //            var puntos_des = $('#puntos_desc_val').data('cupon');
                data.push(envio);
                data.push(cupon);
                data.push(pago);
                data.push(token);

                $.ajax({
                    url: "datos",
                    type: 'post',
                    data: {envio: envio, token: token, cupon:cupon, pago:pago, editorial:JSON.stringify(editorial_push)},
                    //contentType: 'application/json', 
                    dataType: "html",
//                    dataType: "json",
                    beforeSend: function(){
                            //$('.totales-carrito .spinner').show();
                            //$('.error-carrito.payment').hide();
                        },
                    success: function (respuesta) {

                        var resp = JSON.parse(respuesta);
                        var mensaje = resp.mensaje;
                        var exito = resp.exito;

                        if(exito == '0'){
                            $('.error-carrito.payment').removeClass('oculto');
                            $('.error-carrito.payment').addClass('mensaje-error');
                            $('.error-carrito.payment').html(mensaje)
                            $('#realizar-pago').addClass('btnpago-activo');
                            $('#realizar-pago').removeClass('btnpago-check');
                            $('#loadergif-pago').addClass('oculto');
                            $('.img-procesando').hide();
                            alert(resp.mensaje);
                        }else {

                            var ciudad = $('#checkout-ciudad').val();
                            var pais = $('#pais-id option:selected').val();
                            var cp = $('#checkout-cp').val();
                            var calle = $('#checkout-calle').val();
                            var nombre = $('#checkout-nombre').val();
                            var apellido = $('#checkout-apellido').val();
                            var telefono = $('#checkout-telefono').val().replace(/ /g, "");

                            var radio_envio = $('input:radio[name=radio-envio]:checked').val()
                            var num_ped = resp.numped;

                            $.ajax({
                                url: "ship",
                                type: "post",
                                data: {ciudad: ciudad, pais: pais, cp: cp, calle: calle, nombre: nombre, apellido: apellido, telefono: telefono, radio_envio: radio_envio},

                                success: function (data) {
            //                                guia = data;
                                /*************************Correp*********************/
            //                            var form = $('#compra-form');
                                    var correo = $('#checkout-correo').val();//var correo=$('#email_envio').val(); 
                                    var calle = $('#checkout-calle').val();
            //                            var numero= $('#num_ext_envio').val();
            //                            var calle2= form.find('#num_int_envio').val();
                                    var colonia = $('#checkout-colonia').val();
                                    var del = $('#checkout-ciudad').val();
                                    var cp = $('#checkout-cp').val();
                                    var edo = $('#estado-id option:selected').val();
                                    var tel = $('#checkout-telefono').val();
                                    var nom = $('#checkout-nombre').val()+" "+ $('#checkout-apellido').val();
                                    var total = $('#compra-total').val();
                                    var resumen = $('#resumen-compra-bloque').val();
                                    var fecha = 'fecha_normal';
                                    var time = 'timestamp';
                                    var num_guia = data;
                                    var precio_paquete = $('#compra-envio').val();
                                    var datos_respuesta = respuesta;
                                    var radio_envio = $('input:radio[name=radio-envio]:checked').val();
                                    var products=[];

                                    $('.row-pedido-libro').each(function(index, value){
                                        var item = {};
                                        item[0] = $('.btn-cantidad',value).data('producto');
            //                                    var id  = $('.id-carrito',value).val();
                                        item[1] = $('.checkout2-autor',value).text();
                                        item[2] = $('.checkout2-titulo',value).text();
                                        item[3] = $('.precio-promo',value).val();
                                        item[4] = $('.checkout2-precio',value).text();
                                        item[5] = $('#compra-cantidad-'+item[0]+'').val();
                                        item[6] = $('.checkout2-ed-clave',value).text();
            //                                    item[5] = $('#cantidad-' + id).text();
                                        products.push(item);
                                    });
                                    $.ajax({
                                        url: "track",
                                        type: "post",
                                        data: {num_ped:num_ped, guia:num_guia},
                                        success: function(respuesta){
                                            $.ajax({
                                                url: 'enviar-correo',
                                                type: 'post',
                                                data: { num_ped:num_ped, ciudad:ciudad, correo:correo,calle:calle,numero:numero,colonia:colonia,cp:cp,edo:edo,tel:tel,nom:nom,del: del,fecha:fecha,time:time, num_guia:num_guia, precio_paquete: precio_paquete, products:products, datos_respuesta: datos_respuesta, radio_envio: radio_envio, editoriales:editoriales},
                                                dataType: "html",
                                                success: function(respuesta){      
                                                },
                                            });
                                        },
                                    });
                                },
                                error: function () {
                                    $('#realizar-pago').addClass('btnpago-activo');
                                    $('#realizar-pago').removeClass('btnpago-check');
                                    $('#loadergif-pago').addClass('oculto');
                                    alert("Something went wrong");
                                }
                            });
                        }; 
                    },
                });
            });
            
        }, 10000)
    }else{
        $('#modal-ver-unf').modal('show');
    }
    
    return false;
});


$('input:radio[name=fpago]').on('click', function(){
    document.getElementById('form-pago').setAttribute('data-passed', true);
    pago = $('input:radio[name=fpago]:checked').val();
    forma_de_pagar = $('input:radio[name=fpago]:checked').val();
    $("#aceptar").prop('disabled', false);
    $("#aplica-desc").removeClass('disabled-p');
    $("#aplica-puntos").removeClass('disabled-p');
    $('#tarjetaselect').hide(1000);
    $('#realizar-pago').addClass('btnpago-activo');
    $('#realizar-pago').removeClass('btnpago-check');

    if(forma_de_pagar == 'card'){
        $('#tarjetaselect').show(1000);
    }
});

$('input:radio[name=ttarjeta][value=DEB]').on('click', function(){
    $(".formas").show(500);
    $('input:radio[name=forma][value=1]').prop("checked", true);
});

$('input:radio[name=ttarjeta][value=CRE]').on('click', function(){
    $(".formas").show(500);
});

$('#boton-guardar-compra').on('click', function(e){
    var form = $('#compra-form');
    var correo = $('#etcorreo').val();
    var nombre = $('#nombre_envio').val() +' '+ $('#apellidos_envio').val();
    var valor = form.find('#nombre_envio').val().length;
    var valor1 = form.find('#nombre_envio').val().length;
    var valor2 = form.find('#apellidos_envio').val().length;
    var valor3 = form.find('#telefono_envio').val().length;
    var valor4 = form.find('#calle_envio').val().length;
    var valor5 = form.find('#num_ext_envio').val().length;
    var valor6 = form.find('#ciudad_envio').val().length;
    var valor7 = form.find('#pais-id').val().length;
    var valor8 = form.find('#estado-id').val().length;
    var valor9 = form.find('#cp_envio').val().length;
    var valor10 = form.find('#delegacion_envio').val().length;
    var ciudad = form.find('#ciudad_envio').val();
    var pais = form.find('#pais-id').val();
    var cp = form.find('#cp_envio').val();
    var calle = form.find('#calle_envio').val();
    var estado = form.find('#estado-id').val();
    if(valor ==0){
        $('html, body').animate({scrollTop: ( $('#nombre_envio').offset().top - 125 ) }, 1000);
        form.find('#nombre_envio').active();
    }else if(valor2 ==0){ 
        $('html, body').animate({scrollTop: ( $('#apellidos_envio').offset().top - 125 ) }, 1000);
        form.find('#apellidos_envio').active();
    }else if(valor3 ==0){ 
        $('html, body').animate({scrollTop: ( $('#telefono_envio').offset().top - 125 ) }, 1000);
        form.find('#telefono_envio').active();
    }else if(valor4 ==0){ 
        $('html, body').animate({scrollTop: ( $('#calle_envio').offset().top - 125 ) }, 1000);
        form.find('#calle_envio').active();
    }else if(valor5 ==0){  
        $('html, body').animate({scrollTop: ( $('#num_ext_envio').offset().top - 125 ) }, 1000);
        form.find('#num_ext_envio').active();
    }else if(valor6 ==0){ 
        $('html, body').animate({scrollTop: ( $('#ciudad_envio').offset().top - 125 ) }, 1000);
        form.find('#ciudad_envio').active();
    }else if(valor9 ==0){ 
        $('html, body').animate({scrollTop: ( $('#cp_envio').offset().top - 125 ) }, 1000);
        form.find('#cp_envio').active();
    }else if(valor8 ==0){ 
    }else if(valor7 ==0){ 
    }else if(valor10 ==0){ 
    }
    else{  
        $('#loadergif').removeClass('oculto');
        document.getElementById('titulo-dic').setAttribute('data-passed', true);
        fbq('track', 'CompleteRegistration', {
            mail: correo, 
            name: nombre, 
            zipcode: cp, 
            city: ciudad, 
        }); 
        $.ajax({
            url: "checkout/tarifas",
            type: "post",
            data: {ciudad: ciudad, pais: pais, cp: cp, calle: calle, estado:estado},
            
            success: function (data) {
                if(data.length>0){ 
                    $('.fedex').html(data)
                    $('#preenvio').hide();
                    $('#form-envio').addClass('titulo-verde');
                    $('#form-envio').removeClass('titulo-direccion');
                    $('#titulo-dic').addClass('titulo-direccion');
                    $('#titulo-dic').removeClass('titulo-verde');
                    $('#loadergif').addClass('oculto');
                    $('#mostrar-datos-compra').hide(1000);
                    $('#mostrar-datos-envio').show(1000);
                    $('body').animate({scrollTop: $('#mostrar-botones').offset().top }, 1000);
                    // Validacion de inputs de envio

                    $('input:radio[name=tenvio]').on('click', function(){
                        //alert($('input:radio[name=tenvio]:checked').val());
                        document.getElementById('form-envio').setAttribute('data-passed', true);
                        var tipo = $('input:radio[name=tenvio]:checked').val();
                        tipo_envio = tipo.split('/')[0];
                        precio_envio = tipo.split('/')[1];
                        timestamp = tipo.split('/')[2];
                        fecha_normal = tipo.split('/')[3];
                        $('#form-envio').addClass('titulo-direccion');
                        $('#form-envio').removeClass('titulo-verde');
                        $('#mostrar-datos-envio').hide(1000);
                        $('#form-pago').addClass('titulo-verde');
                        $('#form-pago').removeClass('titulo-direccion');
                        $('#mostrar-datos-pago').show(1000);
                    });
                }else{
                    alert("Actualmente el servicio de envios tiene inconvenientes");
                    $('#loadergif').addClass('oculto'); 
                }
            },
            error: function () {
                alert("Actualmente el servicio de envios tiene inconvenientes");
                $('#loadergif').addClass('oculto');
            }
        });
    }
    e.preventDefault();
    e.stopImmediatePropagation();  
});

//agregar correo a carrito
$('#btninv').click(function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var correo = $('#etcorreo').val();
    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    $('#loadergif-correo').removeClass('oculto');
    if (caract.test(correo) == false){
        alert("Correo invalido");
        $('#loadergif-correo').addClass('oculto');
        return false;
    }else{
    document.getElementById('titulo-info').setAttribute('data-passed', true);
    $.ajax({
        url: "checkout/actualizar",
        type: "post",
        data: {correo: correo},
        dataType: "json",
        success: function (data) {
            $('#loadergif-correo').addClass('oculto');
            $('#titulo-info').addClass('titulo-informacion-sin');
            $('#titulo-info').removeClass('titulo-informacion');
            $('#titulo-dic').addClass('titulo-verde');
            $('#titulo-dic').removeClass('titulo-direccion');
            $('#contacto-check').hide(1000);
            $('#cont-end').hide(1000);
            $('#mostrar-datos-compra').show(1000);
            $("#emailtext_envio").val(correo); 
              fbq('track', 'SubmitApplication', {
                mail: correo, 
              });          
            //document.location.href='#titulo-dic';
            //$('html, body').animate({scrollTop: $('#titulo-info').offset().top }, 1000);
        },
        error: function () {
            alert("Something went wrong");
        }
    });
}

});



//eliminar libro de carrito
$('div').on('click', '.cantidad', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id1 = $(this).attr('id');
    var dict = {};
    dict.id = id1;
    $.ajax({
        url: "checkout/eliminar",
        type: "get",
        data: dict,
        success: function (data) {
            window.location.replace("checkout");
        },
        error: function () {
            alert("Something went wrong");
        }
    });
});

//medidas de la altura
jQuery(document).ready(function($){
     jQuery(window).scroll(function(){
         if(window.innerHeight < 850 && window.innerWidth > 990 && $('.checkout-block').length > 0)
         {
            if ($('#resumen-compra-bloque').offset().top + $('#resumen-compra-bloque').height()>= $('.footer').offset().top - 10)
            {
                $('#resumen-compra-bloque').addClass('derecha-altura');
                $('#resumen-compra-bloque').removeClass('derecha');
             }
             if ($(document).scrollTop() + window.innerHeight < $('.footer').offset().top)
             {
                $('#resumen-compra-bloque').removeClass('derecha-altura');
                $('#resumen-compra-bloque').addClass('derecha');
             }
         }
     });
    var num_rand = Math.floor(Math.random() * 100) + 1 ;
    $("#nom_cliente_compra").text(nombre_consumidor[num_rand]);
    $("#lugar_compra").text(estados_compra[num_rand]);
    $("#producto_compra").text(nombre_libro_compra[num_rand]);
    $("#tiempo_compra").text(horas_compra[num_rand]);
    $("#ap_cliente_compra").text(apellidos_consumidor[num_rand]);
    $("#com_id_src").attr("href", "https://www.lectorum.com.mx/site/ver?id="+id_libro_compra[num_rand]);
    $("#imagen_prod_compra").attr("src", "https://www.lectorum.com.mx/images/portadas/"+imagen_libro_compra[num_rand]);
})

/*Mostrar libro en modal*/
$('#contacto-check').on('click', '.btnsesion', function () {
    var id1 = $(this).attr('id');
    var dict = {};
    dict.id = id1;
    $.ajax({
        url: "checkout/ver",
        type: "get",
        data: dict,
        success: function (data) {
            if(data){
                $('.jquery-modal').addClass('modal-blue');
                $('.modal-body').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('.terminos').on('click', '.terminos-modal-open', function () {
    $.ajax({
        url: "checkout/terminos",
        type: "get",
        success: function (data) {
            if(data){
                $('.modal-body-terminos').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('.terminos-detalle').on('click', '.terminos-modal-open', function () {
    $.ajax({
        url: "../checkout/terminos",
        type: "get",
        success: function (data) {
            if(data){
                $('.modal-body-terminos').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('.terminos-detalle').on('click', '.privacidad-modal-open', function () {
    $.ajax({
        url: "../checkout/privacidad",
        type: "get",
        success: function (data) {
            if(data){
                $('.modal-body-priv').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('.terminos-detalle').on('click', '.devolucion-modal-open', function () {
    $.ajax({
        url: "../checkout/devolucion",
        type: "get",
        success: function (data) {
            if(data){
                $('.modal-body-dev').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

//////////////////Titulos Login////////////////////

$('#datos-cont').on('click', 'h3', function () {

        var data = $('#datos-cont').attr('data-act'); 
        if(data == 1){
            $('#datos-cont').attr('data-act', '0');
            $('#datos-env').attr('data-act', '0');
            $('#datos-ped').attr('data-act', '0');

            $('#mostrar-datos-contacto').hide(1000);
            $('#datos-cont h3').removeClass('is-active');  
            $('#datos-cont').removeClass('titulo-g');
            $('#signupform-nombre').prop('disabled', true);
            $('#signupform-ap_paterno').prop('disabled', true);
            $('#datosContUs').prop('disabled', true);
            $('#datosContUs').fadeOut('slow');
            $('#datosBasicos').fadeIn('slow');
        }else if(data == 0){ 
            $('#datos-cont').attr('data-act', '1');
            $('#datos-env').attr('data-act', '0');
            $('#datos-ped').attr('data-act', '0');

            $('#mostrar-datos-pedidos').hide(1000);
            $('#mostrar-datos-envio-us').hide(1000);
            $('#mostrar-datos-contacto').show(1000);

            $('#datos-env h3').removeClass('is-active');
            $('#datos-ped h3').removeClass('is-active');
            $('#datos-cont h3').addClass('is-active');

            $('#datos-cont').addClass('titulo-g');
            $('#datos-env').removeClass('titulo-g');
            $('#datos-ped').removeClass('titulo-g');

            $('#telefono_en').prop('disabled', true);
            $('#calle_en').prop('disabled', true);
            $('#num_ext_en').prop('disabled', true);
            $('#num_int_en').prop('disabled', true);
            $('#ciudad_en').prop('disabled', true);
            $('#codigo_pot').prop('disabled', true);
            $('#col_envio').prop('disabled', true);
            $('#btnActDatos').prop('disabled', true);
            $('#btnActDatos').fadeOut('slow');
            $('#datosEnvio').fadeIn('slow');
        }
});

$('#datos-env').on('click', 'h3', function () {

    var data = $('#datos-env').attr('data-act'); 
        if(data == 1){
            $('#datos-cont').attr('data-act', '0');
            $('#datos-env').attr('data-act', '0');
            $('#datos-ped').attr('data-act', '0');

            $('#mostrar-datos-contacto').hide(1000);
            $('#mostrar-datos-envio-us').hide(1000);

            $('#datos-env h3').removeClass('is-active');
            $('#datos-cont h3').removeClass('is-active');
            $('#datos-ped h3').removeClass('is-active');

            $('#datos-ped').removeClass('titulo-g');
            $('#datos-env').removeClass('titulo-g');
            $('#datos-cont').removeClass('titulo-g');


            $('#telefono_en').prop('disabled', true);
            $('#calle_en').prop('disabled', true);
            $('#num_ext_en').prop('disabled', true);
            $('#num_int_en').prop('disabled', true);
            $('#ciudad_en').prop('disabled', true);
            $('#codigo_pot').prop('disabled', true);
            $('#col_envio').prop('disabled', true);
            $('#btnActDatos').prop('disabled', true);
            $('#btnActDatos').fadeOut('slow');
            $('#datosEnvio').fadeIn('slow');

            $('#signupform-nombre').prop('disabled', true);
            $('#signupform-ap_paterno').prop('disabled', true);
            $('#datosContUs').prop('disabled', true);
            $('#datosContUs').fadeOut('slow');
            $('#datosBasicos').fadeIn('slow');

        }else if(data == 0){
            $('#datos-cont').attr('data-act', '0');
            $('#datos-env').attr('data-act', '1');
            $('#datos-ped').attr('data-act', '0');

            $('#mostrar-datos-pedidos').hide(1000);
            $('#mostrar-datos-contacto').hide(1000);
            $('#mostrar-datos-envio-us').show(1000);

            $('#datos-cont h3').removeClass('is-active');
            $('#datos-ped h3').removeClass('is-active');
            $('#datos-env h3').addClass('is-active');

            $('#datos-env').addClass('titulo-g');
            $('#datos-cont').removeClass('titulo-g');
            $('#datos-ped').removeClass('titulo-g');

            $('#signupform-nombre').prop('disabled', true);
            $('#signupform-ap_paterno').prop('disabled', true);
            $('#datosContUs').prop('disabled', true);
            $('#datosContUs').fadeOut('slow');
            $('#datosBasicos').fadeIn('slow');
            $('#telefono_en').prop('disabled', true);
            
            $('#calle_en').prop('disabled', true);
            $('#num_ext_en').prop('disabled', true);
            $('#num_int_en').prop('disabled', true);
            $('#ciudad_en').prop('disabled', true);
            $('#codigo_pot').prop('disabled', true);
            $('#col_envio').prop('disabled', true);
            $('#btnActDatos').prop('disabled', true);
            $('#btnActDatos').fadeOut('slow');
            $('#datosEnvio').fadeIn('slow');
        }

});

$('#datos-ped').on('click', 'h3', function () {

    var data = $('#datos-ped').attr('data-act');
    if(data == 1){
        $('#datos-cont').attr('data-act', '0');
        $('#datos-env').attr('data-act', '0');
        $('#datos-ped').attr('data-act', '0');

        $('#mostrar-datos-contacto').hide(1000);
        $('#mostrar-datos-envio-us').hide(1000);
        $('#mostrar-datos-pedidos').hide(1000);

        $('#datos-env h3').removeClass('is-active');
        $('#datos-cont h3').removeClass('is-active');
        $('#datos-ped h3').removeClass('is-active');

        $('#datos-ped').removeClass('titulo-g');
        $('#datos-env').removeClass('titulo-g');
        $('#datos-cont').removeClass('titulo-g');


        $('#telefono_en').prop('disabled', true);
        $('#calle_en').prop('disabled', true);
        $('#num_ext_en').prop('disabled', true);
        $('#num_int_en').prop('disabled', true);
        $('#ciudad_en').prop('disabled', true);
        $('#codigo_pot').prop('disabled', true);
        $('#col_envio').prop('disabled', true);
        $('#btnActDatos').prop('disabled', true);
        $('#btnActDatos').fadeOut('slow');
        $('#datosEnvio').fadeIn('slow');

        $('#signupform-nombre').prop('disabled', true);
        $('#signupform-ap_paterno').prop('disabled', true);
        $('#datosContUs').prop('disabled', true);
        $('#datosContUs').fadeOut('slow');
        $('#datosBasicos').fadeIn('slow');

    }else if(data == 0){
        $('#datos-cont').attr('data-act', '0');
        $('#datos-env').attr('data-act', '0');
        $('#datos-ped').attr('data-act', '1');
        $('#mostrar-datos-contacto').hide(1000);
        $('#mostrar-datos-envio-us').hide(1000);
        $('#mostrar-datos-pedidos').show(1000);

        $('#datos-env h3').removeClass('is-active');
        $('#datos-cont h3').removeClass('is-active');
        $('#datos-ped h3').addClass('is-active');

        $('#datos-ped').addClass('titulo-g');
        $('#datos-env').removeClass('titulo-g');
        $('#datos-cont').removeClass('titulo-g');


        $('#telefono_en').prop('disabled', true);
        $('#calle_en').prop('disabled', true);
        $('#num_ext_en').prop('disabled', true);
        $('#num_int_en').prop('disabled', true);
        $('#ciudad_en').prop('disabled', true);
        $('#codigo_pot').prop('disabled', true);
        $('#col_envio').prop('disabled', true);
        $('#btnActDatos').prop('disabled', true);
        $('#btnActDatos').fadeOut('slow');
        $('#datosEnvio').fadeIn('slow');

        $('#signupform-nombre').prop('disabled', true);
        $('#signupform-ap_paterno').prop('disabled', true);
        $('#datosContUs').prop('disabled', true);
        $('#datosContUs').fadeOut('slow');
        $('#datosBasicos').fadeIn('slow');
    }
});

///////////////////Titulos checkout////////////////////////////

$('.terminos').on('click', '.privacidad-modal-open', function () {
    $.ajax({
        url: "checkout/privacidad",
        type: "get",
        success: function (data) {
            if(data){
                $('.modal-body-priv').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('.terminos').on('click', '.devolucion-modal-open', function () {
    $.ajax({
        url: "checkout/devolucion",
        type: "get",
        success: function (data) {
            if(data){
                $('.modal-body-dev').html(data);
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('#titulo-info').on('click', 'h3', function () {
    var passed = $('#titulo-info').data('passed');
    if(passed){
        $('#contacto-check').show(1000);
        $('#cont-end').show(1000);
        $('#mostrar-datos-pago').hide(1000);
        $('#mostrar-datos-compra').hide(1000);
        $('#mostrar-datos-envio').hide(1000);
        $('#titulo-info').removeClass('titulo-informacion-sin');
        $('#titulo-info').addClass('titulo-informacion');
        $('#form-envio').removeClass('titulo-verde');
        $('#form-envio').addClass('titulo-direccion');
        $('#titulo-dic').removeClass('titulo-verde');
        $('#titulo-dic').addClass('titulo-direccion');
        $('#form-pago').removeClass('titulo-verde');
        $('#form-pago').addClass('titulo-direccion');
    }
});

$('#titulo-dic').on('click', 'h3', function () {
    var passed = $('#titulo-dic').data('passed');
    if(passed){
        $('#mostrar-datos-compra').show(1000);
        $('#mostrar-datos-pago').hide(1000);
        $('#mostrar-datos-envio').hide(1000);
        $('#contacto-check').hide(1000);
        $('#cont-end').hide(1000);
        $('#form-envio').removeClass('titulo-verde');
        $('#form-envio').addClass('titulo-direccion');
        $('#titulo-dic').removeClass('titulo-direccion');
        $('#titulo-dic').addClass('titulo-verde');
        $('#form-pago').removeClass('titulo-verde');
        $('#form-pago').addClass('titulo-direccion');
        $('#titulo-info').addClass('titulo-informacion-sin');
        $('#titulo-info').removeClass('titulo-informacion');
    }
});

$('#form-envio').on('click', 'h3', function () {
    var passed = $('#form-envio').data('passed');
    if(passed){
        $('#mostrar-datos-compra').hide(1000);
        $('#mostrar-datos-pago').hide(1000);
        $('#mostrar-datos-envio').show(1000);
        $('#contacto-check').hide(1000);
        $('#cont-end').hide(1000);
        $('#form-envio').addClass('titulo-verde');
        $('#form-envio').removeClass('titulo-direccion');
        $('#titulo-dic').addClass('titulo-direccion');
        $('#titulo-dic').removeClass('titulo-verde');
        $('#form-pago').removeClass('titulo-verde');
        $('#form-pago').addClass('titulo-direccion');
        $('#titulo-info').addClass('titulo-informacion-sin');
        $('#titulo-info').removeClass('titulo-informacion');
    }
});

$('#form-pago').on('click', 'h3', function () {
    var passed = $('#form-envio').data('passed');
    if(passed){
        $('#mostrar-datos-compra').hide(1000);
        $('#mostrar-datos-pago').show(1000);
        $('#mostrar-datos-envio').hide(1000);
        $('#contacto-check').hide(1000);
        $('#cont-end').hide(1000);
        $('#form-envio').removeClass('titulo-verde');
        $('#form-envio').addClass('titulo-direccion');
        $('#titulo-dic').addClass('titulo-direccion');
        $('#titulo-dic').removeClass('titulo-verde');
        $('#form-pago').addClass('titulo-verde');
        $('#form-pago').removeClass('titulo-direccion');
        $('#titulo-info').addClass('titulo-informacion-sin');
        $('#titulo-info').removeClass('titulo-informacion');
    }
});

//restar cantidades
$('div').on('click', '.productoMenos', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var libro = $(this).attr('id');
    var  cant = $(this).attr('value');
    if(cant >1){
    var cantidad = parseInt(cant)-1;
} else {
    var cantidad = 1;
}
    $.ajax({
        url: "bolsa/actualizarcantidad",
        type: "post",
        data: {libro, cantidad},
        dataType: "json",
        success: function (data) {
            window.location.replace("bolsa");
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});
//sumar cantidades
$('div').on('click', '.productoMas', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var libro = $(this).attr('id');
    var  cant = $(this).attr('value');
    var cantidad = parseInt(cant)+1;
    var array = [libro, cantidad];
    $.ajax({
        url: "bolsa/actualizarcantidad",
        type: "post",
        data: {libro, cantidad},
        dataType: "json",
        success: function (data) {
            window.location.replace("bolsa");
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

//eliminar libro de carrito
$('div').on('click', '.quitar', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id1 = $(this).attr('id');
    var dict = {};
    dict.id = id1;
    $.ajax({
        url: "bolsa/eliminar",
        type: "get",
        data: dict,
        success: function (data) {
            window.location.replace("bolsa");
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

//mostrar libros de carrito en bolsa
$('#icono-bolsa').on('click', '.bolsa', function (e) {
    e.stopImmediatePropagation();
    var url = String(new URL(document.URL));
    var ver = url.search("site/ver");
    if(ver>0){
        var bolsa = "../bolsa";
    }else{
        var bolsa = "bolsa";
    }
    var val =1;
    $.ajax({
        url: bolsa,
        type: "get",
        data: val,
        success: function (data) {
                window.location.replace(bolsa); 
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('#icono-bolsa-mob').on('click', '.bolsa', function (e) {
    e.stopImmediatePropagation();
    var url = String(new URL(document.URL));
    var ver = url.search("site/ver");
    if(ver>0){
        var bolsa = "../bolsa";
    }else{
        var bolsa = "bolsa";
    }
    var val =1;
    $.ajax({
        url: bolsa,
        type: "get",
        data: val,
        success: function (data) {
                window.location.replace(bolsa);                 
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('.agregar-relacionado').click(function(event){
        var btn = $('.addbolsa');
        var libro = $(this).data('id');
        btn.attr('disabled', 'disabled');
        var cantidad = '1';
        if (cantidad != '' && libro != '') {
            $.ajax({
                url: "../bolsa/agrega",
                type: 'post',
                data: { libro, cantidad },
                dataType: "json",
                success: function (respuesta) {
                    if (respuesta.exito == 1){
                        if($(window).width()>768){
                            $('.mensaje-carrito').css("display","block");
                            $('.mensaje-carrito').addClass('.exito');
                        }else{
                            $('.mensaje-carrito2').css("display","block");
                            $('.mensaje-carrito2').addClass('.exito');
                        }
                        $('.relacionado-agregado.'+libro+'').show();
                        $.ajax({
                            url: '../bolsa/actualizar',
                            type: 'post',
                            success: function (respuesta) {
                                $('.bolsa').empty   ()
                                $('.bolsa').html(respuesta)
                            }
                        });
                    } 
                },
                complete: function () {
                    btn.removeAttr('disabled');
                }
            });
        }
    event.stopImmediatePropagation();
})

$('.agregar-bolsa').on('click', '.addbolsa', function (event) {
        var btn = $('.addbolsa');
        var libro = $('#agregar-bolsa').data('id');
        btn.attr('disabled', 'disabled');
        var cantidad = '1';
        //alert(libro + '<- libro, cantidad->'+cantidad);
        if (cantidad != '' && libro != '') {
            $.ajax({
                url: "../bolsa/agrega",
                type: 'post',
                data: { libro, cantidad },
                dataType: "json",
                success: function (respuesta) {
                    if (respuesta.exito == 1){
                        if($(window).width()>768){
                            $('.mensaje-carrito').css("display","block");
                            $('.mensaje-carrito').addClass('.exito');
                        }else{
                            $('.mensaje-carrito2').css("display","block");
                            $('.mensaje-carrito2').addClass('.exito');
                        }
                        $.ajax({
                            url: '../bolsa/actualizar',
                            type: 'post',
                            success: function (respuesta) {
                                $('.bolsa').empty   ()
                                $('.bolsa').html(respuesta)
                            }
                        });
                    } 
                },
                complete: function () {
                    btn.removeAttr('disabled');
                }
            });
        }
    
    event.stopImmediatePropagation();
});

$('.btn-accion-rel').on('click', '.addbolsa-rel', function (event) {
        var btn = $('.addbolsa-rel');
        var libro = $(this).parent('.btn-accion-rel').data('id');
        btn.attr('disabled', 'disabled');
        var cantidad = '1';
        //alert(libro + '<- libro, cantidad->'+cantidad);
        if (cantidad != '' && libro != '') {
            $.ajax({
                url: "../bolsa/agrega",
                type: 'post',
                data: { libro, cantidad },
                dataType: "json",
                success: function (respuesta) {
                    $.ajax({
                            url: '../bolsa/actualizar',
                            type: 'post',
                            success: function (respuesta) {
                                $('.bolsa').empty   ()
                                $('.bolsa').html(respuesta)
                                alert("Libro agregado al carrito");
                                //$('.bombers-compradas').html(respuesta);
                                //$('.bolsa #carrito-span').text($('#menu-especial .bomber').length);
                            }
                        });

                },
                complete: function () {
                    btn.removeAttr('disabled');
                }
            });
        }
    
    event.stopImmediatePropagation();
});

$('div').on('click', '.comprarbolsa', function (event) {
        
    var baseurl="<?php print Yii::app()->request->baseUrl;?>";
    var btn = $('.btn-comprar');
    var libro = $('.comprarbolsa').attr('id');
    btn.attr('disabled', 'disabled');
    var cantidad = '1';
    //alert(libro + '<- libro, cantidad->'+cantidad);
    if (cantidad != '' && libro != '') {
        $.ajax({
            url: "../bolsa/agrega",
            type: 'post',
            data: { libro, cantidad },
            dataType: "json",
            success: function (respuesta) {
                $.ajax({
                        url: '../bolsa/actualizar',
                        type: 'post',
                        success: function (respuesta) {
                            $('.bolsa').empty   ()
                            $('.bolsa').html(respuesta)
                            window.location.replace("../checkout/");
                            //$('.bombers-compradas').html(respuesta);
                            //$('.bolsa #carrito-span').text($('#menu-especial .bomber').length);
                        }
                    });

            },
            complete: function () {
                btn.removeAttr('disabled');
            }
        });
    }
    event.stopImmediatePropagation();
});
    



//Buscar

$('#buscar2').change(function(){
    $('#buscar').val($('#buscar2').val())
});

$('.busqueda').on('click', '.imgbuscador', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id1 = $('#buscar').val();
    var dict = {};
    dict.id = id1;
    var url = String(new URL(document.URL));
    var ver = url.search("catalogo/");
    var ver2 = url.search("checkout/");
    var ver3 = url.search("nosotros/");
    if((ver > 0)||(ver2 > 0)||(ver3 > 0)){
        var catalogo = "../catalogo/index";
    }else {
        var catalogo = "catalogo/index";
    }
    if(id1){
        $.ajax({
            url: catalogo,
            type: "get",
            data: dict,
            success: function (data) {
                    window.location.replace(""+catalogo+"?buscar="+id1);
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }else{
        alert('No ha escrito ningún termino de busqueda');
    }
});
$('.busqueda2').on('click', '.imgbuscador', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id1 = $('#buscar2').val();
    var dict = {};
    dict.id = id1;
    var url = String(new URL(document.URL));
    var ver = url.search("catalogo/");
    var ver2 = url.search("checkout/");
    var ver3 = url.search("nosotros/");
    if((ver > 0)||(ver2 > 0)||(ver3 > 0)){
        var catalogo = "../catalogo/index";
    }else {
        var catalogo = "catalogo/index";
    }
    if(id1){
        $.ajax({
            url: catalogo,
            type: "get",
            data: dict,
            success: function (data) {
                    window.location.replace(""+catalogo+"?buscar="+id1);
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }else{
        alert('No ha escrito ningún termino de busqueda');
    }
});
$('#bus_main').on('click', '.img_bus', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id1 = $('#inpbus').val();
    var dict = {};
    dict.id = id1;
    var url = String(new URL(document.URL));
    var ver = url.search("catalogo/");
    var ver2 = url.search("checkout/");
    var ver3 = url.search("nosotros/");
    if((ver > 0)||(ver2 > 0)||(ver3 > 0)){
        var catalogo = "../catalogo/index";
    }else {
        var catalogo = "catalogo/index";
    }
    if(id1){ 
        $.ajax({
            url: catalogo,
            type: "get",
            data: dict,
            success: function (data) {
                    window.location.replace(""+catalogo+"?buscar="+id1); 
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }else{
        alert('No ha escrito ningún termino de busqueda');
    }
});

$('.busqueda').keypress(function (e) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        e.preventDefault();
        e.stopImmediatePropagation();
        var id1 = $('#buscar').val();
        var dict = {};
        dict.id = id1;
        var url = String(new URL(document.URL));
        var ver = url.search("catalogo/");
        var ver2 = url.search("checkout/");
        var ver3 = url.search("nosotros/");
        if((ver > 0)||(ver2 > 0)||(ver3 > 0)){
            var catalogo = "../catalogo/index";
        }else {
            var catalogo = "catalogo/index";
        }
        if(id1){
            $.ajax({
                url: catalogo,
                type: "get",
                data: dict,
                success: function (data) {
                    window.location.replace(""+catalogo+"?buscar="+id1);
                },
                error: function () {
                    alert("Something went wrong");
                }
            });
        }else{
            alert('No ha escrito ningún termino de busqueda');
        } 
    }
});
$('.busqueda2').keypress(function (e) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        e.preventDefault();
        e.stopImmediatePropagation();
        var id1 = $('#buscar2').val();
        var dict = {};
        dict.id = id1;
        var url = String(new URL(document.URL));
        var ver = url.search("catalogo/");
        var ver2 = url.search("checkout/");
        var ver3 = url.search("nosotros/");
        if((ver > 0)||(ver2 > 0)||(ver3 > 0)){
            var catalogo = "../catalogo/index";
        }else {
            var catalogo = "catalogo/index";
        }
        if(id1){
            $.ajax({
                url: catalogo,
                type: "get",
                data: dict,
                success: function (data) {
                    window.location.replace(""+catalogo+"?buscar="+id1);
                },
                error: function () {
                    alert("Something went wrong");
                }
            });
        }else{
            alert('No ha escrito ningún termino de busqueda');
        } 
    }
});

$('#bus_main').keypress(function (e) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        e.preventDefault();
        e.stopImmediatePropagation();
        var id1 = $('#inpbus').val();
        var dict = {};
        dict.id = id1;
        var url = String(new URL(document.URL));
        var ver = url.search("catalogo/");
        var ver2 = url.search("checkout/");
        var ver3 = url.search("nosotros/");
        if((ver > 0)||(ver2 > 0)||(ver3 > 0)){
            var catalogo = "../catalogo/index";
        }else {
            var catalogo = "catalogo/index";
        }
        if(id1){
            $.ajax({
                url: catalogo,
                type: "get",
                data: dict,
                success: function (data) {
                        window.location.replace(""+catalogo+"?buscar="+id1);
                },
                error: function () {
                    alert("Something went wrong");
                }
            });
        }else{
            alert('No ha escrito ningún termino de busqueda');
        } 
    }
});

/*Mostrar lista de libros dependiendo del sello*/
$('#main-sellos').on('click', '.sel-sello', function () {
    $('.btnNov').removeClass('seleccion');
    $('.sel-sello').children().removeClass('seleccion');
    $('.sel-cat').children().removeClass('seleccion');
    $(this).children().addClass('seleccion');
    var titulo = $(this).data('titulo');
    dataLayer.push({
        'ecommerce': {
            'impressions': {                    
                    'name': titulo,
            }
        }
    });
});

/*Mostrar lista de libros dependiendo de la categoria*/
$('#main-cat').on('click', '.sel-cat', function () {
    $('.btnNov').removeClass('seleccion');
    $('.sel-sello').children().removeClass('seleccion');
    $('.sel-cat').children().removeClass('seleccion');
    $(this).children().addClass('seleccion');
    var titulo = $(this).data('titulo');
    dataLayer.push({
        'ecommerce': {
            'impressions': {                    
                    'name': titulo,
            }
        }
    });
});

$('div').on('click', '.agregar-bolsa .addbolsa', function () {

    var titulo = $('.titulo-mod').data('titulos');
    var categoria = $("input[name=nombre_tema]").val();
    var isbn = $("input[name=isbn]").val();
    var sello = $("input[name=nombre_sello]").val();
    var precio = $(".precio-mod").text().split('$');

    fbq('track', 'AddToCart', {
        content_name: titulo,
        content_category: categoria,
        content_ids: String(isbn),
        content_type: sello,
        contents: '1',
        value: parseFloat(precio[1]),
        currency: 'MXN'
    });

    dataLayer.push({
      'event': 'addToCart',
      'ecommerce': {
        'currencyCode': 'MXN',
        'add': {                                // 'add' actionFieldObject measures.
          'products': [{                        //  adding a product to a shopping cart.
            'name': titulo,
            'id': String(isbn),
            'price': parseFloat(precio[1]),
            'brand': sello,
            'category': categoria,
            'quantity': 1
           }]
        }
      }
    });

});
$('.btn-quitar-checkout').click(function(){
    
    var id = $(this).val();
    
    $.ajax({
        url:'eliminar',
        type:'post',
        data: {id:id},
        success: function(){
            
            var titulo = $('#compra-cantidad-'+id+'').data('titulo');
            var isbn = $('#compra-cantidad-'+id+'').data('isbn'); 
            var cantidad = $('#compra-cantidad-'+id+'').val(); 
            var precio = $('#compra-cantidad-'+id+'').data('precio'); 
            var sello = $('#compra-cantidad-'+id+'').data('sello'); 
            var tema = $('#compra-cantidad-'+id+'').data('tema');
            var subtotal = $('#compra-cantidad-'+id+'').data('subtotal');
            
            var editorial_clave = $('#compra-cantidad-'+id+'').data('editclave');
            var actual_subtotal = parseFloat($('#compra-editorial-'+editorial_clave+'').data('subtotal'));
            var diferencia_precio = parseFloat($('#compra-cantidad-'+id+'').data('subtotal'));

            $('#compra-editorial-'+editorial_clave+'').data('subtotal', actual_subtotal-diferencia_precio);
            
            if ($('#compra-editorial-'+editorial_clave+'').data('subtotal') == '0'){
                $('#compra-editorial-'+editorial_clave+'').remove();
            }
            
            $('#compra-cantidad-'+id+'').remove();
            $('#libro-checkout1-'+id+'').remove();
            $('#libro-checkout2-'+id+'').remove();
            $('#libro-checkout3-'+id+'').remove();
            $('#libro-checkout4-'+id+'').remove();
            
            $('#carrito-span').text((parseInt($('#carrito-span').text()))-1)
            
            
            if($('#cupon-id').val() != ''){
                $('#compra-subtotal').val((parseFloat($('#compra-subtotal').val()-(subtotal*(1-$('#cupon-porcentaje').val())))).toFixed(2))
                $('.checkout-numero-subtotal').text('$ '+$('#compra-subtotal').val())
                $('.cupon-subtotal').text('$ '+$('#compra-subtotal').val())
                
                $('#cupon-descuento').val($('#cupon-descuento').val()-($('#cupon-porcentaje').val()*subtotal))
            } else {
                $('.cupon-subtotal').text('$ '+$('#compra-subtotal').val())
                $('#compra-subtotal').val((parseFloat($('#compra-subtotal').val()-(subtotal))).toFixed(2))
                $('.checkout-numero-subtotal').text('$ '+$('#compra-subtotal').val())
                $('.cupon-subtotal').text('$ '+$('#compra-subtotal').val())                
            }
            
            if($('compra-envio').val() != ''){
                $('#compra-total').val((parseFloat($('#compra-subtotal').val())).toFixed(2))
                $('.checkout-numero-total').text('$ '+$('#compra-total').val())
            } else {
                $('#compra-total').val((parseFloat($('#compra-subtotal').val())+parseFloat($('#compra-envio').val())).toFixed(2))
                $('.checkout-numero-total').text('$ '+$('#compra-total').val())
            }
            
            if ($('#compra-subtotal').val() == 0){
                $(location).attr('href','../site/index');
            }

            dataLayer.push({
              'event': 'removeFromCart',
              'ecommerce': {
                'remove': {                // 'remove' actionFieldObject measures.
                  'products': [{           //  removing a product to a shopping cart.
                      'name': titulo,
                      'id': isbn,
                      'price': subtotal,
                      'brand': sello,
                      'category': tema,
                      'quantity': cantidad
                  }]
                }
              }
            });
        }
    })
    
});

$('div').on('click', '.close-modal', function () {
    $(".modal-body").html("");
}); 



$('#modal-ver-libros').on('modal:after-close', function() {
    $(".modal-body").html("");
});


$('body').on('click', '.sellomen a', function() {

        $('.ul_submenu').toggleClass("oculto");

});

$('body').on('click', '.temamen a', function() {

        $('.ul_submenu2').toggleClass("oculto");

});

//if($('.datos-compra-bolsa').length){
//    onCheckout();
//}
//
//if($('.check-card-block').length){
//    onConfirma();
//}
//
//if($('.checkout-confirmaoxxo-block').length){
//    onConfirmaOxxo();
//}
//
//if($('.checkout-confirmapaypal-block').length){
//    onConfirmapaypal();
//}

if($('.descr-lib').length){
    libros_page();
    if($(window).width() < 581){
    last_child();
    }
}

$('#regRecomPop').on('click', '#btnCorreo2', function() {
    $('#form-pop').unbind('submit').on('submit', function(e) {
        var form = $(this);
        var usuario = $('#correopopup').val();
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        e.preventDefault();
        if (caract.test(usuario)){
            $.ajax({
                url: "site/correos",
                type: "post",
                data: {email:usuario},
                
                success: function (respuesta) {
                    
                },
                error: function () {
                }
            });
            $.ajax({
                url: "site/checkuser",
                type: "post",
                data: {usuario:usuario},
                
                success: function (respuesta) {
                    if(respuesta == 'si' ){
                        alert('El nombre de usuario ya está registrado en la base datos y no puede ser usado');
                        return false;
                    }else{
                        form[0].submit();
                    }
                },
                error: function () {
                }
            });
        }else{
            alert("Correo invalido");
            return false;
        }
    });
});

$('#signup-form').on('click', '#regist-us', function() {
    $('#signup-form').unbind('submit').on('submit', function(e) {
        var form = $(this);
        var usuario = $('#signupform-usuario').val();
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        e.preventDefault();
        if (caract.test(usuario)){
            $.ajax({
                url: "site/correos",
                type: "post",
                data: {email:usuario},
                
                success: function (respuesta) {
                    
                },
                error: function () {
                }
            });
            $.ajax({
                url: "site/checkuser",
                type: "post",
                data: {usuario:usuario},
                
                success: function (respuesta) {
                    if(respuesta == 'si' ){
                        alert('El nombre de usuario ya está registrado en la base datos y no puede ser usado');
                        return false;
                    }else{
                        form[0].submit();
                    }
                },
                error: function () {
                }
            });
        }else{
            alert("Correo invalido");
            return false;
        }
    });
});

$('#regRecom').on('click', '#btnCorreo', function() {
    $('#form-correos').unbind('submit').on('submit', function(e) {
        var form = $(this);
        var usuario = $('#correo-recompensas').val();
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        e.preventDefault();
        if (caract.test(usuario)){
            $.ajax({
                url: "site/correos",
                type: "post",
                data: {email:usuario},
                
                success: function (respuesta) {
                    
                },
                error: function () {
                }
            });
            $.ajax({
                url: "site/checkuser",
                type: "post",
                data: {usuario:usuario},
                
                success: function (respuesta) {
                    if(respuesta == 'si' ){
                        alert('El nombre de usuario ya está registrado en la base datos y no puede ser usado');
                        return false;
                    }else{
                        form[0].submit();
                    }
                },
                error: function () {
                }
            });
        }else{
            alert("Correo invalido");
            return false;
        }
    });
});

$('#user-update2').on('click', '#btnActDatos', function() {
    $('#user-update2').unbind('submit').on('submit', function(e) {
        var form = $(this);
        usuario = $('#signupcliform-usuario').val();
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        e.preventDefault();
        if (caract.test(usuario)){
            $.ajax({
                url: "../site/checkuser",
                type: "post",
                data: {usuario:usuario},
                
                success: function (respuesta) {
                    if(respuesta == 'si' ){
                        alert('El nombre de usuario ya está registrado en la base datos y no puede ser usado');
                        return false;
                    }else{
                        $('#paises_d2').prop('disabled', false);
                        $('#deleg_d2').prop('disabled', false);
                        $('#esta-d2').prop('disabled', false);
                        form[0].submit();
                    }
                },
                error: function () {
                }
            });
        }else{
            alert("Correo invalido");
            return false;
        }
    });
});

$('#mostrar-datos-envio-us').on('click', '#btnActDatos', function() {
    $('#envio-form').on('beforeSubmit', function(e) {
        $('#paise-id').prop('disabled', false);
        $('#deleg_envio').prop('disabled', false);
        $('#esta-id').prop('disabled', false);
    });
});

$('.form-dat-user').on('click', '#btnActDatos', function() {
    $('#user-update').on('beforeSubmit', function(e) {
        $('#paises_d').prop('disabled', false);
        $('#deleg_d').prop('disabled', false);
        $('#esta-d').prop('disabled', false);
    });
});

$('#clientesform-nombre, #clientesform-apellidos, #telefono_en, #calle_en, #num_ext_en, #ciudad_en, #codigo_pos, #col_d').on('input', function () {

    var form = $('#user-update');
    var valor1 = form.find('#clientesform-nombre').val().length;
    var valor2 = form.find('#clientesform-apellidos').val().length;
    var valor3 = form.find('#telefono_en').val().length;
    var valor4 = form.find('#calle_en').val().length;
    var valor5 = form.find('#num_ext_en').val().length;
    var valor6 = form.find('#ciudad_en').val().length;
    var valor7 = form.find('#codigo_pos').val().length;

    if(!valor1 == 0 && !valor2 == 0 && valor3 == 10 && !valor4 == 0 && !valor5 == 0 && !valor6 == 0 && valor7 == 5 && $('#aceptar-bases').is(':checked')){
        form.find('#btnActDatos').removeClass('disabled-p');
    }
});

$('#aceptar-bases').on('click', function() {
    var form = $('#user-update');
    var valor1 = form.find('#clientesform-nombre').val().length;
    var valor2 = form.find('#clientesform-apellidos').val().length;
    var valor3 = form.find('#telefono_en').val().length;
    var valor4 = form.find('#calle_en').val().length;
    var valor5 = form.find('#num_ext_en').val().length;
    var valor6 = form.find('#ciudad_en').val().length;
    var valor7 = form.find('#codigo_pos').val().length;

    if(!valor1 == 0 && !valor2 == 0 && valor3 == 10 && !valor4 == 0 && !valor5 == 0 && !valor6 == 0 && valor7 == 5 && $('#aceptar-bases').is(':checked')){
        form.find('#btnActDatos').removeClass('disabled-p');
    }
});

$('#signupcliform-nombre, #signupcliform-apellidos, #telefono_en2, #calle_en2, #num_ext_en2, #ciudad_en2, #codigo_pos2, #col_d2').on('input', function () {

    var form = $('#user-update2');
    var valor1 = form.find('#signupcliform-nombre').val().length;
    var valor2 = form.find('#signupcliform-apellidos').val().length;
    var valor3 = form.find('#telefono_en2').val().length;
    var valor4 = form.find('#calle_en2').val().length;
    var valor5 = form.find('#num_ext_en2').val().length;
    var valor6 = form.find('#ciudad_en2').val().length;
    var valor7 = form.find('#codigo_pos2').val().length;

    if(!valor1 == 0 && !valor2 == 0 && valor3 == 10 && !valor4 == 0 && !valor5 == 0 && !valor6 == 0 && valor7 == 5 && $('#aceptar-bases2').is(':checked')){
        form.find('#btnActDatos').removeClass('disabled-p');
    }
});

$('#aceptar-bases2').on('click', function() {
    var form = $('#user-update2');
    var valor1 = form.find('#signupcliform-nombre').val().length;
    var valor2 = form.find('#signupcliform-apellidos').val().length;
    var valor3 = form.find('#telefono_en2').val().length;
    var valor4 = form.find('#calle_en2').val().length;
    var valor5 = form.find('#num_ext_en2').val().length;
    var valor6 = form.find('#ciudad_en2').val().length;
    var valor7 = form.find('#codigo_pos2').val().length;

    if(!valor1 == 0 && !valor2 == 0 && valor3 == 10 && !valor4 == 0 && !valor5 == 0 && !valor6 == 0 && valor7 == 5 && $('#aceptar-bases2').is(':checked')){
        form.find('#btnActDatos').removeClass('disabled-p');
    }
});

$('#numero').on('keypress paste change keydown keyup',function () {
    var tipo_01 = $('#tipo_pago_01');
    var tipo_02 = $('#tipo_pago_02');
    var tipo_03 = $('#tipo_pago_03');
    if($(this).val().length >= 15){
        var tip = Conekta.card.getBrand($(this).val());
        if(tip == 'visa'){
            tipo_01.show('slow');
            tipo_02.hide('slow');
            tipo_03.hide('slow');
        }else if(tip == 'mastercard'){
            tipo_01.hide('slow');
            tipo_02.show('slow');
            tipo_03.hide('slow');
        }else if(tip == 'amex'){
            tipo_01.hide('slow');
            tipo_02.hide('slow');
            tipo_03.show('slow');
        }
    }else{
        tipo_01.hide('slow');
        tipo_02.hide('slow');
        tipo_03.hide('slow');
    }
});


/*****************/
$('#cp_envio').change(function () {
    var form = $('#compra-form');
    var cp = form.find(this).val();
    $('#loadergif-cp').removeClass('oculto');
    $.ajax({
        url: "https://api-codigos-postales.herokuapp.com/v2/codigo_postal/".concat(cp),
        type: "get",
        dataType: 'JSON',
        success: function (data) {
            $('#loadergif-cp').addClass('oculto');
            $('#delegacion_envio').val(data.municipio);
            $('#pais-id option[value="42"]').prop('selected', true);
            $('#colonia_envio').val(data.colonias);
            $('#colonia_envio').find('option').remove().end();
            for(i = 0; i < data.colonias.length; i++){
                $('#colonia_envio').append('<option value="'+data.colonias[i]+'">'+data.colonias[i]+'</option>');
            }
            if(data.estado == 'México'){
                var estado = 'Estado de México';
            }else if(data.estado == 'Coahuila de Zaragoza'){
                var estado = 'Coahuila';
            }else if(data.estado == 'Veracruz de Ignacio de la Llave' ){
                var estado = 'Veracruz-Llave';
            }else if(data.estado == 'Michoacán de Ocampo' ){
                var estado = 'Michoacan';
            }else{
                var estado = data.estado;
            }
            $.ajax({
                url: "checkout/estadomundo",
                type: "post",
                data: {estado:estado},
                dataType: 'JSON',
                success: function(datos){
                    $('#estado-id').find('option').remove().end().append('<option value="'+datos.id+'">'+datos.estadonombre+'</option>');
                    $('#estado-id option[value="'+datos.id+'"]').attr("selected", true);
                },
                error: function(){

                }
            });
        },
        error: function () {
            $('#loadergif-cp').addClass('oculto');
            alert("Something went wrong");
        }
    });

});

$( "#input-puntos" ).change(function() {
    var max = parseInt($(this).attr('max'));
    var min = parseInt($(this).attr('min'));
    if ($(this).val() > max)
    {
      $(this).val(max);
    }
    else if ($(this).val() < min)
    {
      $(this).val(min);
    }       
}); 

$('#codigo_pot').change(function () {
    var form = $('#envio-form');
    var cp = form.find(this).val();
    var url = String(new URL(document.URL));
    var ver = url.search("site/ver");
    var ver2 = url.search("nosotros/datos");
    if(ver>0 || ver2>0){
        var esta_url = "../checkout/estadomundo";
    }else{
        var esta_url = "checkout/estadomundo";
    }
    $.ajax({
        url: "https://api-codigos-postales.herokuapp.com/v2/codigo_postal/".concat(cp),
        type: "get",
        dataType: 'JSON',
        success: function (data) {
            $('#deleg_envio').val(data.municipio);
            $('#paise-id option[value="42"]').prop('selected', true);
            $('#col_envio').val(data.colonias);
            $('#col_envio').find('option').remove().end();
            for(i = 0; i < data.colonias.length; i++){
                $('#col_envio').append('<option value="'+data.colonias[i]+'">'+data.colonias[i]+'</option>');
            }
            if(data.estado == 'México'){
                var estado = 'Estado de México';
            }else if(data.estado == 'Coahuila de Zaragoza'){
                var estado = 'Coahuila';
            }else{
                var estado = data.estado;
            }
            $.ajax({
                url: esta_url,
                type: "post",
                data: {estado:estado},
                dataType: 'JSON',
                success: function(data){
                    $('#esta-id').find('option').remove().end().append('<option value="'+data.id+'">'+data.estadonombre+'</option>');
                    $('#esta-id option[value="'+data.id+'"]').attr("selected", true);
                },
                error: function(){

                }
            });
        },
        error: function () {
            $('#loadergif-cp').addClass('oculto');
            alert("Something went wrong");
        }
    });

});

$('#codigo_pos').change(function () {
    var form = $('#user-update');
    var cp = form.find(this).val();
    $.ajax({
        url: "https://api-codigos-postales.herokuapp.com/v2/codigo_postal/".concat(cp),
        type: "get",
        dataType: 'JSON',
        success: function (data) {
            $('#deleg_d').val(data.municipio);
            $('#paises_d option[value="42"]').prop('selected', true);
            $('#col_ed').val(data.colonias);
            $('#col_d').find('option').remove().end();
            for(i = 0; i < data.colonias.length; i++){
                $('#col_d').append('<option value="'+data.colonias[i]+'">'+data.colonias[i]+'</option>');
            }
            if(data.estado == 'México'){
                var estado = 'Estado de México';
            }else if(data.estado == 'Coahuila de Zaragoza'){
                var estado = 'Coahuila';
            }else{
                var estado = data.estado;
            }
            $.ajax({
                url: "../checkout/estadomundo",
                type: "post",
                data: {estado:estado},
                dataType: 'JSON',
                success: function(data){
                    $('#esta-d').find('option').remove().end().append('<option value="'+data.id+'">'+data.estadonombre+'</option>');
                    $('#esta-d option[value="'+data.id+'"]').attr("selected", true);
                },
                error: function(){

                }
            });
        },
        error: function () {
            alert("Something went wrong");
            $('#loadergif-cp').addClass('oculto');
        }
    });

});

$('#codigo_pos2').change(function () {
    var form = $('#user-update2');
    var cp = form.find(this).val();
    $.ajax({
        url: "https://api-codigos-postales.herokuapp.com/v2/codigo_postal/".concat(cp),
        type: "get",
        dataType: 'JSON',
        success: function (data) {
            $('#deleg_d2').val(data.municipio);
            $('#paises_d2 option[value="42"]').prop('selected', true);
            $('#col_ed2').val(data.colonias);
            $('#col_d2').find('option').remove().end();
            for(i = 0; i < data.colonias.length; i++){
                $('#col_d2').append('<option value="'+data.colonias[i]+'">'+data.colonias[i]+'</option>');
            }
            if(data.estado == 'México'){
                var estado = 'Estado de México';
            }else if(data.estado == 'Coahuila de Zaragoza'){
                var estado = 'Coahuila';
            }else{
                var estado = data.estado;
            }
            $.ajax({
                url: "../checkout/estadomundo",
                type: "post",
                data: {estado:estado},
                dataType: 'JSON',
                success: function(data){
                    $('#esta-d2').find('option').remove().end().append('<option value="'+data.id+'">'+data.estadonombre+'</option>');
                    $('#esta-d2 option[value="'+data.id+'"]').attr("selected", true);
                },
                error: function(){

                }
            });
        },
        error: function () {
            alert("Something went wrong");
        }
    });

});

$('#puntosdeventa').on('click', function() {
    $(this).addClass('menu')
    $('#contactom').removeClass('menu')
});

$('#datosBasicos').on('click', function() {
    $(this).fadeOut('slow');
    $('#signupform-nombre').prop('disabled', false);
    $('#signupform-ap_paterno').prop('disabled', false);
    $('#datosContUs').prop('disabled', false);
    $('#datosContUs').fadeIn('slow');
});


$('#datosEnvio').on('click', function() {
    $(this).fadeOut('slow');
    $('#telefono_en').prop('disabled', false);
    $('#calle_en').prop('disabled', false);
    $('#num_ext_en').prop('disabled', false);
    $('#num_int_en').prop('disabled', false);
    $('#ciudad_en').prop('disabled', false);
    $('#codigo_pot').prop('disabled', false);
    $('#col_envio').prop('disabled', false);
    $('#btnActDatos').prop('disabled', false);
    $('#btnActDatos').fadeIn('slow');
});

$('#contactom').on('click', function() {
    $(this).addClass('menu')
    $('#puntosdeventa').removeClass('menu')
});

$('.login-user').on('click', '.visualDatos', function () {
    var id1 = $(this).attr('id');
    var url = $('#urlpedidos').val();
    $.ajax({
        url: url,
        type: "post",
        data: {id:id1},
        success: function (data) {
            if(data){
                $('#modal-ver-pedidos').modal('show');
                $('.modal-body').html(data);
            }
            else{
                alert("Error mostrando Pedido")
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });
 
});

$( document ).ready(function() {
    $('img.lazy').Lazy({
        afterLoad: function(element) {
            $(element).addClass('loaded');
        },
    });
    var url = String(new URL(document.URL));
    var contacto = url.search("contacto");
    var punto = url.search("puntos-venta");
    var novedades = url.search("novedades=1");
    var catalogo = url.search("mas_vendidos=1");
    var popUp = $('#popUp').val();

    if(contacto>0){
        $('#contactom').addClass('menu');
    }else if(punto>0) {
        $('#puntosdeventa').addClass('menu');
    }else if(novedades>0){
        $('#nove-men').addClass('menu');
    }else if(catalogo>0){
        $('#catal-men').addClass('menu');
    }
    if(popUp == 0){
        $('#modal-ver-promo').modal('show');
    }
});

$(window).scroll(function() {
    var url = String(new URL(document.URL));
    var site = url.search('site');
    var libro = url.search('ver');
    var login = url.search('login');
    var index = $('#navbar').data('rut');

    if($(this).scrollTop() > 100 && site>0 || $(this).scrollTop() > 100 && index == 'index'){
        $('#navbar').addClass('color');
    }else if($(this).scrollTop() < 100 && site>0 && libro<0 || $(this).scrollTop() < 100 && index == 'index'){
        $('#navbar').removeClass('color').addClass('colorfade');
    }
});

$(document).ready(main);
    var contador = 1;
    function main () {
        $('.menu_bar').click(function(){
            if (contador ===1) {
                $('nav').animate({
                    left: '0'
                });
                contador = 0;
            } else {
                contador = 1;
                $('nav').animate({
                    left: '-100%'
                });
            }
        });
    }

//var btnLogin = document.getElementById('iniciar');
var btnregistrar = document.getElementById('btnCorreo');
var login = document.getElementById('logearse');
var closelogin = document.getElementById('cerrar');
var gracias = document.getElementById('gracias');
var recompensa = document.getElementById('recompensa');
var novedades = document.getElementById('novedades');
var librerias = document.getElementById('librerias');
var testimonios = document.getElementById('testimonios');
var puntos = document.getElementById('puntos-venta');
var contacto = document.getElementById('contacto');
var footer = document.getElementById('foot');

//btnLogin.onclick= function() 
//    login.style.visibility = "visible";
//    recompensa.style.display = "none";
//    gracias.style.display = "none";
//    novedades.style.display = "none";
//    librerias.style.display = "none";
//    testimonios.style.display = "none";
//    puntos.style.display = "none";
//    contacto.style.display = "none";
//    footer.style.display = "none";
//};
//btnregistrar.onclick= function() {
//    login.style.visibility = "visible";
//    recompensa.style.display = "none";
//    gracias.style.display = "none";
//    novedades.style.display = "none";
//    librerias.style.display = "none";
//    testimonios.style.display = "none";
//    puntos.style.display = "none";
//    contacto.style.display = "none";
//    footer.style.display = "none";
//};
//closelogin.onclick= function() {
//    login.style.visibility = "hidden";
//    recompensa.style.display = "block";
//    gracias.style.display = "block";
//    novedades.style.display = "block";
//    librerias.style.display = "block";
//    testimonios.style.display = "block";
//    puntos.style.display = "block";
//    contacto.style.display = "block";
//    footer.style.display = "block";
//
//};

function sendMenu(titulo){

    dataLayer.push({
        'ecommerce': {
            'impressions': {                    
                    'name': titulo,
            }
        }
    });
}

$(document).ready(function(){

//    function cuponDesc() {
//        var cupon = $('#input-cupon').val();
//        $.ajax({
//            url: "checkout/cupon",
//            type: "post",
//            data: {cupon:cupon},
//            success: function (data) {
//                var envio_pre = $('#precio-envio').data('envio');
//                if(data){
//                    $('#desc-p').removeClass('oculto');
//                    var resp = JSON.parse(data);
//                    var cup_id = resp.id;
//                    var porcentaje = resp.porcentaje;
//                    var porcentaje = porcentaje / 100;
//                    var codigo = resp.codigo;
//                    var total_descuento = (porcentaje * <?php echo $totalp; ?>).toFixed(2);
//                    $('#cupon-descuento-total').val(porcentaje);
//                    $('#cupon-id').val(cup_id);
//                    $('#cupon_desc_val').html('-$'+total_descuento);
//                    $('#desc-puntos').addClass('oculto');
//                    document.getElementById('cupon_desc_val').setAttribute('data-cupon', total_descuento);
//                    document.getElementById('puntos_desc_val').removeAttribute('data-cupon');
//                    if(envio_pre){
//                        $('#total-envio').html('$MXN ' + ((<?php echo $totalp; ?> + envio_pre) - total_descuento).toFixed(2));
//                        document.getElementById('precio-total').setAttribute('data-total', ((<?php echo $totalp; ?> + envio_pre) - total_descuento).toFixed(2));
//                    }else{
//                        $('#total-envio').html('$MXN ' + (<?php echo $totalp; ?> - total_descuento).toFixed(2));
//                        document.getElementById('precio-total').setAttribute('data-total', (<?php echo $totalp; ?> - total_descuento).toFixed(2));
//                    }
//                    $('#cancelar-puntos').addClass('oculto');
//                    $('#aplica-puntos').removeClass('oculto');
//                }else{
//                    alert('El cupón no existe o expiró la fecha de su uso');
//                    $('#cupon_desc_val').html('0');
//                    if(envio_pre){
//                        $('#total-envio').html('$MXN ' + (<?php echo $totalp; ?> + envio_pre));
//                        $('#precio-total').val(<?php echo $totalp; ?> + envio_pre);
//                        $('#total-envio').html('$MXN ' + ((<?php echo $totalp; ?> + envio_pre)).toFixed(2));
//            document.getElementById('precio-total').setAttribute('data-total', ((<?php echo $totalp; ?> + envio_pre)).toFixed(2));
//                    }else{
//                        $('#total-envio').html('$MXN ' + <?php echo $totalp; ?>);
//                        $('#precio-total').val(<?php echo $totalp; ?>);
//                        document.getElementById('precio-total').setAttribute('data-total', (<?php echo $totalp; ?>).toFixed(2));
//                    }
//                    $('#desc-puntos').addClass('oculto');
//                    $('#desc-p').addClass('oculto');
//                    $('#cupon-descuento-total').val('');
//                    $('#cupon-id').val('');
//                    $('#puntos_desc_val').addClass('oculto');
//                    document.getElementById('puntos_desc_val').removeAttribute('data-cupon');
//                    document.getElementById('cupon_desc_val').removeAttribute('data-cupon');
//                    $('#cancelar-puntos').addClass('oculto');
//                    $('#aplica-puntos').removeClass('oculto');
//                }
//            },
//            error: function () {
//              $('#loadergif-cp').addClass('oculto');  
//            }
//        });
//    }
    

});
