
$(document).ready(function(){

    
    function mismaAltura(midiv) {
        var alto = 0;
        setTimeout(function(){
            midiv.each(function() {
                altura = $(this).height();
                if(altura > alto) {
                    alto = altura;
                }
            });
            midiv.height(alto);
        }, 200);
    }
    function mismaAltura2(midiv) {
        var alto = 0;
        midiv.each(function() {
            altura = $(this).height();
            if(altura > alto) {
                alto = altura;
            }
        });
        midiv.height(alto);
    }


//    function mismaAltura2(midiv, midiv2) {
//        if (midiv > midiv2){
//            $(".col-checkout-pedido").height(midiv);
//        } else {
//            $(".col-datos-total").height(midiv2);
//        }
//    }

    $('#editoriales-nav').click(function(){
        $('#tabla-editoriales-nav').toggle();
        $('#nav-desplegar').toggle();
        $('#nav-cerrar').toggle();
    });

    $('#categorias-nav').click(function(){
        $('#categorias-nav').toggleClass('no-bottom');
        $('#categorias-contenedor').toggle();
        $('#categorias-desplegar').toggle();
        $('#categorias-cerrar').toggle();
    });

    $('#usuario-desplegar-contacto').click(function(){
        $('#usuario-cerrar-contacto').show();
        $('#usuario-desplegar-contacto').hide();
    });
    $('#usuario-cerrar-contacto').click(function(){
        $('#usuario-cerrar-contacto').hide();
        $('#usuario-desplegar-contacto').show();
    });
    $('#usuario-desplegar-envio').click(function(){
        $('#usuario-cerrar-envio').show();
        $('#usuario-desplegar-envio').hide();
    });
    $('#usuario-cerrar-envio').click(function(){
        $('#usuario-cerrar-envio').hide();
        $('#usuario-desplegar-envio').show();
    });
    $('#usuario-desplegar-pedidos').click(function(){
        $('#usuario-cerrar-pedidos').show();
        $('#usuario-desplegar-pedidos').hide();
    });
    $('#usuario-cerrar-pedidos').click(function(){
        $('#usuario-cerrar-pedidos').hide();
        $('#usuario-desplegar-pedidos').show();
    });

    mismaAltura($(".col-checkout-carrito"));
    mismaAltura($(".relacionados-altura"));
    mismaAltura($(".relacionados-altura2"));
    mismaAltura($(".relacionados-altura3"));
    mismaAltura($(".row-carrusel-img"));
    mismaAltura($(".row-carrusel-img3"));
    mismaAltura($(".categoria-altura"));
    mismaAltura($(".wrap-evento"));

    $(window).resize(function(){
        if ($('.misma-altura-2').height() > 1) {
            mismaAltura2($(".misma-altura-2"));
        }
        if ($('.col-checkout-carrito').height() > 1) {
            mismaAltura($(".col-checkout-carrito"));
        }
        $(".col-checkout-carrito").css('height', 'inherit')
        $(".relacionados-altura").css('height', 'inherit')
        $(".relacionados-altura2").css('height', 'inherit')
        $(".relacionados-altura3").css('height', 'inherit')
        $(".row-carrusel-img").css('height', 'inherit')
        $(".row-carrusel-img3").css('height', 'inherit')
        $(".categoria-altura").css('height', 'inherit')
        mismaAltura($(".col-checkout-carrito"));
        mismaAltura($(".relacionados-altura"));
        mismaAltura($(".relacionados-altura2"));
        mismaAltura($(".relacionados-altura3"));
        mismaAltura($(".row-carrusel-img"));
        mismaAltura($(".row-carrusel-img3"));
        mismaAltura($(".categoria-altura"));
    });

    $('.btn-ir-pagar').click(function(){
        $('#checkout-proceso-1').removeClass('paso-proceso-seleccion');
        $('#checkout-proceso-2').addClass('paso-proceso-seleccion');
        $('#container-checkout-1').hide();
        $('#container-checkout-2').show();
        $('#container-subcheckout-3').hide();
        $('#container-subcheckout-4').hide();
        $('.checkout-mob-2').show();
        $('.checkout-mob-1').hide();

        mismaAltura($(".misma-altura-pedido"));
        mismaAltura2($(".misma-altura-2"));
        if($('#cupon-porcentaje').val() != ''){
            $('#canjear-cupon2').addClass('canjeado');
            $('#canjear-cupon2').attr('disabled', true);
            $('#canjear-cupon2').removeAttr('id');
            $('#canjear-cupon').addClass('canjeado');
            $('#canjear-cupon').attr('disabled', true);
            $('#canjear-cupon').removeAttr('id');
        }
    });
    $('.btn-regresar-carrito').click(function(){
        $('#checkout-proceso-2').removeClass('paso-proceso-seleccion');
        $('#checkout-proceso-1').addClass('paso-proceso-seleccion');
        $('#container-checkout-2').hide();
        $('#container-checkout-1').show();
        $('.checkout-mob-1').show();
        $('.checkout-mob-2').hide();

        if($('#cupon-porcentaje').val() != ''){
            $('#canjear-cupon').addClass('canjeado');
            $('#canjear-cupon').attr('disabled', true);
            $('#canjear-cupon').removeAttr('id');
            $('#canjear-cupon2').addClass('canjeado');
            $('#canjear-cupon2').attr('disabled', true);
            $('#canjear-cupon2').removeAttr('id');
        }
    });

    $('.btn-continuar-envio').click(function(){
        var ciudad = $('#checkout-ciudad').val();
        var pais = $('#pais-id option:selected').val();
        var cp = $('#checkout-cp').val();
        var calle = $('#checkout-calle').val();
        var estado = $('#estado-id option:selected').val();
        $.ajax({
            url: "tarifas",
            type: "post",
            data: {ciudad: ciudad, pais: pais, cp: cp, calle: calle, estado:estado},
            beforeSend: function(){
                $('.img-procesando').show();
                $('.btn-continuar-envio').attr('disabled', true);
                $('.btn-continuar-envio').addClass('btn-disabled');
            },
            success: function (fedex_js) {
                var fedex = JSON.parse(fedex_js);
                if (fedex.exito == '1'){
                    $('.envio-standard').val(fedex['standard']['tipo']);
                    $('.envio-standard').data('envio', fedex['standard']['costo']);
                    $('.envio-std-costo').text('$ '+fedex['standard']['costo']);
                    $('.envio-express').val(fedex['express']['tipo']);
                    $('.envio-express').data('envio', fedex['express']['costo']);
                    $('.envio-exp-costo').text('$ '+fedex['express']['costo']);

                    $('#checkout-proceso-2').removeClass('paso-proceso-seleccion');
                    $('#checkout-proceso-3').addClass('paso-proceso-seleccion');
                    $('#container-subcheckout-2').hide();
                    $('#container-subcheckout-3').show();
                    $('.checkout-mob-2').hide();
                    $('.checkout-mob-3').show();
                    $('.resumen-correo-p').text($('#checkout-correo').val());
                    $('.resumen-enviar-p').text($('#checkout-calle').val()+' '+ $('#checkout-colonia').val()+' '+ $('#checkout-ciudad').val());
                }
            },
            error: function () {
                //estos datos solo se descomentan para pruebas de calculo de costos de envio
                // por que la aplicacion de prueba de fedex falla muy seguido

                $('.envio-express').val('FEDEX_EXPRESS_SAVER');
                $('.envio-standard').val('STANDARD_OVERNIGHT');

                $('#checkout-proceso-2').removeClass('paso-proceso-seleccion');
                $('#checkout-proceso-3').addClass('paso-proceso-seleccion');
                $('#container-subcheckout-2').hide();
                $('#container-subcheckout-3').show();
                $('.checkout-mob-2').hide();
                $('.checkout-mob-3').show();

                $('.resumen-correo-p').text($('#checkout-correo').val());
                $('.resumen-enviar-p').text($('#checkout-calle').val()+' '+ $('#checkout-colonia').val()+' '+ $('#checkout-ciudad').val());
                //estos datos solo se descomentan para pruebas de calculo de costos de envio
                // por que la aplicacion de prueba de fedex falla muy seguido

            },
            complete: function (){
                $('.img-procesando').hide();
                $('.btn-continuar-envio').attr('disabled', false);
                $('.btn-continuar-envio').removeClass('btn-disabled');
            }
        });
    });

    $('.btn-regresar-info').click(function(){
        $('#checkout-proceso-3').removeClass('paso-proceso-seleccion');
        $('#checkout-proceso-2').addClass('paso-proceso-seleccion');
        $('#container-subcheckout-3').hide();
        $('#container-subcheckout-2').show();
        $('.checkout-mob-3').hide();
        $('.checkout-mob-2').show();
    });
    $('.btn-continuar-pago').click(function(){
        $('#checkout-proceso-3').removeClass('paso-proceso-seleccion');
        $('#checkout-proceso-4').addClass('paso-proceso-seleccion');
        $('#container-subcheckout-3').hide();
        $('#container-subcheckout-4').show();
        $('.row-resumen-envio').css('display', 'flex');
        $('.row-pago-mob').show();
        $('.paso-3-mob').hide();
        $('.resumen-correo-p2').text($('#checkout-correo').val());
        $('.resumen-envio-p2').text($('#checkout-calle').val()+' '+ $('#checkout-colonia').val()+' '+ $('#checkout-ciudad').val());
        $('.resumen-envio-p3').text($('input[name=radio-envio]:checked').val())
        $('.resumen-envio-p').text($('input[name=radio-envio]:checked').val())
        $('#compra-envio').val($('input[name=radio-envio]:checked').data('envio'))
        $('#checkout-numero-envio').text('$ '+$('input[name=radio-envio]:checked').data('envio'))
        $('#compra-total').val((parseFloat($('#compra-subtotal').val())+parseFloat($('#compra-envio').val())).toFixed(2))
        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
    });
    $('.btn-regresar-envio').click(function(){
        $('#checkout-proceso-4').removeClass('paso-proceso-seleccion');
        $('#checkout-proceso-3').addClass('paso-proceso-seleccion');
        $('#container-subcheckout-4').hide();
        $('#container-subcheckout-3').show();
        $('.row-pago-mob').hide();
        $('.paso-3-mob').show();
    });

    $('.btn-cantidad').click(function(){
        var producto = $(this).data('producto');
        var cantidad = $('#span-cantidad-'+producto+'').text();
        var operacion = 0;
        if($(this).hasClass('mas')){
            cantidad++;
            operacion = 1;
        } else{
            cantidad--;
            if(cantidad < 1){
                return;
            }
        }
        $.ajax({
            url: '../bolsa/actualizarcantidad',
            type: 'post',
            data: { producto, cantidad },
            dataType: "json",
            success: function (respuesta) {
                if (respuesta.exito == 1) {
                    var sumando = 0;
                    $('#span-cantidad-'+producto+'').text(respuesta.cantidad);
                    $('#span-cantidad2-'+producto+'').text(respuesta.cantidad);
                    $('#span-cantidad3-'+producto+'').text(respuesta.cantidad);
                    $('#span-cantidad4-'+producto+'').text(respuesta.cantidad);

                    $('#compra-cantidad-'+producto+'').val(respuesta.cantidad);
                    var subtotal = $('#compra-cantidad-'+producto+'').data('precio')*respuesta.cantidad;
                    $('#compra-cantidad-'+producto+'').data('subtotal', subtotal);
                    $('#total-libro-'+producto+'').text('$ '+subtotal.toFixed(2));

                    $('.compra-cantidad').each(function(){
                        sumando += $(this).data('subtotal');
                    });
                    $('#compra-subtotal').val(sumando);
                    $('.cupon-subtotal').text('$ '+sumando.toFixed(2));

                    if($('compra-envio').val() != ''){
                        $('#compra-total').val((parseFloat($('#compra-subtotal').val())).toFixed(2))
                        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
                    } else {
                        $('#compra-total').val((parseFloat($('#compra-subtotal').val())+parseFloat($('#compra-envio').val())).toFixed(2))
                        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
                    }

                    var editorial_clave = $('#compra-cantidad-'+producto+'').data('editclave');
                    var actual_subtotal = parseFloat($('#compra-editorial-'+editorial_clave+'').data('subtotal'));
                    var diferencia_precio = parseFloat($('#compra-cantidad-'+producto+'').data('precio'));
                    
                    if(operacion == 1){
                        $('#compra-editorial-'+editorial_clave+'').data('subtotal', actual_subtotal+diferencia_precio);
                    } else{
                        $('#compra-editorial-'+editorial_clave+'').data('subtotal', actual_subtotal-diferencia_precio);
                    }

                } else {
                    alert(respuesta.mensaje)
                }
            },
        });
    });

    $('#canjear-cupon').click(function(e){
        e.preventDefault();
        if ($('#cupon-porcentaje').val() != ''){
            return false;
        }
        var cupon = $('#ingresar-cupon').val();
        $.ajax({
            url: 'cupon',
            type: 'post',
            data: { cupon:cupon },
            success: function(data){
                if(data){
                    $('#canjear-cupon').addClass('canjeado');
                    $('#canjear-cupon').attr('disabled', true);
                    $('#canjear-cupon').removeAttr('id');
                    var respuesta = JSON.parse(data);
                    var cupon_id = respuesta.id;
                    var porcentaje = respuesta.porcentaje;
                    var cupon_porcentaje = porcentaje / 100;
                    var cupon_codigo = respuesta.codigo;

                    $('.cupon-subtotal').text('$ '+($('#compra-subtotal').val()-($('#compra-subtotal').val()*cupon_porcentaje)).toFixed(2).toString());
                    $('#cupon-descuento').val($('#compra-subtotal').val()*cupon_porcentaje.toFixed(2))
                    $('#compra-subtotal').val($('#compra-subtotal').val()-($('#compra-subtotal').val()*cupon_porcentaje));

                    if($('compra-envio').val() != ''){
                        $('#compra-total').val((parseFloat($('#compra-subtotal').val())).toFixed(2))
                        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
                    } else {
                        $('#compra-total').val((parseFloat($('#compra-subtotal').val())+parseFloat($('#compra-envio').val())).toFixed(2))
                        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
                    }

                    $('#cupon-id').val(cupon_id);
                    $('#cupon-codigo').val(cupon_codigo);
                    $('#cupon-porcentaje').val(cupon_porcentaje);
                }
            }
        });
    })
    $('#canjear-cupon2').click(function(e){
        e.preventDefault();
        if ($('#cupon-porcentaje').val() != ''){
            return false;
        }
        var cupon = $('#ingresar-cupon2').val();
        $.ajax({
            url: 'cupon',
            type: 'post',
            data: { cupon:cupon },
            success: function(data){
                if(data){
                    $('#canjear-cupon2').addClass('canjeado');
                    $('#canjear-cupon2').attr('disabled', true);
                    $('#canjear-cupon2').removeAttr('id');
                    var respuesta = JSON.parse(data);
                    var cupon_id = respuesta.id;
                    var porcentaje = respuesta.porcentaje;
                    var cupon_porcentaje = porcentaje / 100;
                    var cupon_codigo = respuesta.codigo;

                    $('.cupon-subtotal').text('$ '+($('#compra-subtotal').val()-($('#compra-subtotal').val()*cupon_porcentaje)).toFixed(2).toString());
                    $('#cupon-descuento').val($('#compra-subtotal').val()*cupon_porcentaje.toFixed(2))
                    $('#compra-subtotal').val($('#compra-subtotal').val()-($('#compra-subtotal').val()*cupon_porcentaje));

                    if($('compra-envio').val() != ''){
                        $('#compra-total').val((parseFloat($('#compra-subtotal').val())).toFixed(2))
                        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
                    } else {
                        $('#compra-total').val((parseFloat($('#compra-subtotal').val())+parseFloat($('#compra-envio').val())).toFixed(2))
                        $('.checkout-numero-total').text('$ '+$('#compra-total').val())
                    }

                    $('#cupon-id').val(cupon_id);
                    $('#cupon-codigo').val(cupon_codigo);
                    $('#cupon-porcentaje').val(cupon_porcentaje);
                }
            }
        });
    })

    $('.radio-tarjeta').click(function(){
        $('.row-tarjeta-pago').show();
    })
    $('.radio-efectivo').click(function(){
        $('.row-tarjeta-pago').hide();
    })
    $('.radio-paypal').click(function(){
        $('.row-tarjeta-pago').hide();
    })

    function numero_coma(num){
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
    var emailPattern = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$";

    function checkCorreo(idInput, pattern) {
        return $(idInput).val().match(pattern) ? true : false;
    }

    function cc_format(value) {
        var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
        var matches = v.match(/\d{4,16}/g);
        var match = matches && matches[0] || ''
        var parts = []
        for (i=0, len=match.length; i<len; i+=4) {
          parts.push(match.substring(i, i+4))
        }
        if (parts.length) {
          return parts.join(' ')
        } else {
          return value
        }
    }
    function vencimiento_format(value) {
        var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
        var matches = v.match(/\d{2,4}/g);
        var match = matches && matches[0] || ''
        var parts = []
        for (i=0, len=match.length; i<len; i+=2) {
          parts.push(match.substring(i, i+2))
        }
        if (parts.length) {
          return parts.join('/')
        } else {
          return value
        }
    }
    function telefono_format(value) {
        var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
        var matches = v.match(/\d{2,10}/g);
        var match = matches && matches[0] || ''
        var parts = []
        for (i=0, len=match.length; i<len; i+=2) {
          parts.push(match.substring(i, i+2))
        }
        if (parts.length) {
          return parts.join(' ')
        } else {
          return value
        }
    }


    $('#check-ofertas').click(function(){
        setTimeout(function(){
            if($("#check-ofertas").prop("checked")){
                $("#check-ofertas2").prop("checked", true);
            } else {
                $("#check-ofertas2").prop("checked",false);
            }
        })
    })
    $('#check-ofertas2').click(function(){
        setTimeout(function(){
            if($("#check-ofertas2").prop("checked")){
                $("#check-ofertas").prop("checked", true);
            } else {
                $("#check-ofertas").prop("checked",false);
            }
        })
    })
    $('#check-publicidad').click(function(){
        setTimeout(function(){
            if($("#check-publicidad").prop("checked")){
                $("#check-publicidad2").prop("checked", true);
            } else {
                $("#check-publicidad2").prop("checked",false);
            }
        })
    })
    $('#check-publicidad2').click(function(){
        setTimeout(function(){
            if($("#check-publicidad2").prop("checked")){
                $("#check-publicidad").prop("checked", true);
            } else {
                $("#check-publicidad").prop("checked",false);
            }
        })
    })
    $('#check-terminos').change(function(){
        if($("#check-terminos").prop("checked")){
            $("#check-terminos2").prop("checked", true);
        } else {
            $("#check-terminos2").prop("checked",false);
        }
    })
    $('#check-terminos2').change(function(){
        if($("#check-terminos2").prop("checked")){
            $("#check-terminos").prop("checked", true);
        } else {
            $("#check-terminos").prop("checked",false);
        }
    })

    $("input[name=radio-envio2]").click(function(){
        $("input[name=radio-envio][value="+$('input[name=radio-envio2]:checked').val()+"]").prop('checked', true)
    });
    $("input[name=radio-envio]").click(function(){
        setTimeout(function(){
            $("input[name=radio-envio2][value="+$('input[name=radio-envio]:checked').val()+"]").prop('checked', true)
        })
    });


    $('#checkout-correo').change(function(){
        $('#checkout-correo2').val($('#checkout-correo').val())
    });
    $('#checkout-correo2').change(function(){
        $('#checkout-correo').val($('#checkout-correo2').val())
    });
    $('#checkout-nombre').change(function(){
        $('#checkout-nombre2').val($('#checkout-nombre').val())
    });
    $('#checkout-nombre2').change(function(){
        $('#checkout-nombre').val($('#checkout-nombre2').val())
    });
    $('#checkout-apellido').change(function(){
        $('#checkout-apellido2').val($('#checkout-apellido').val())
    })
    $('#checkout-apellido2').change(function(){
        $('#checkout-apellido').val($('#checkout-apellido2').val())
    });
    $('#checkout-calle').change(function(){
        $('#checkout-calle2').val($('#checkout-calle').val())
    });
    $('#checkout-calle2').change(function(){
        $('#checkout-calle').val($('#checkout-calle2').val())
    });
    $('#checkout-colonia').change(function(){
        $('#checkout-colonia2').val($('#checkout-colonia').val())
    });
    $('#checkout-colonia2').change(function(){
        $('#checkout-colonia').val($('#checkout-colonia2').val())
    });
    $('#checkout-ciudad').change(function(){
        $('#checkout-ciudad2').val($('#checkout-ciudad').val())
    });
    $('#checkout-ciudad2').change(function(){
        $('#checkout-ciudad').val($('#checkout-ciudad2').val())
    });
    $('#pais-id-2').change(function(){
        $('#pais-id').val($('#pais-id-2').val())
    });
    $('#pais-id').change(function(){
        $('#pais-id-2').val($('#pais-id').val())
    });

    $('#tarjeta-titular').keyup(function(){
        $('#tarjeta-titular2').val($('#tarjeta-titular').val())
    });
    $('#tarjeta-titular2').keyup(function(){
        $('#tarjeta-titular').val($('#tarjeta-titular2').val())
    });
    $('#tarjeta-codigo').keyup(function(){
        $('#tarjeta-codigo2').val($('#tarjeta-codigo').val())
    })
    $('#tarjeta-codigo2').keyup(function(){
        $('#tarjeta-codigo').val($('#tarjeta-codigo2').val())
    })
    $('#tarjeta-num').keyup(function(){
        var tarjeta = cc_format($('#tarjeta-num').val())
        $('#tarjeta-num').val(tarjeta)
        $('#tarjeta-num2').val(tarjeta)
//        alert(tarjeta)
    });
    $('#tarjeta-num2').keyup(function(){
        var tarjeta = cc_format($('#tarjeta-num2').val())
        $('#tarjeta-num').val(tarjeta)
        $('#tarjeta-num2').val(tarjeta)
//        alert(tarjeta)
    });
    $('#tarjeta-vencimiento').keyup(function(){
        var vencimiento = vencimiento_format($('#tarjeta-vencimiento').val())
        $('#tarjeta-vencimiento').val(vencimiento)
        $('#tarjeta-vencimiento2').val(vencimiento)
    })
    $('#tarjeta-vencimiento2').keyup(function(){
        var vencimiento = vencimiento_format($('#tarjeta-vencimiento2').val())
        $('#tarjeta-vencimiento').val(vencimiento)
        $('#tarjeta-vencimiento2').val(vencimiento)
    })
    $('#checkout-telefono').keyup(function(){
        var telefono = telefono_format($('#checkout-telefono').val())
        $('#checkout-telefono').val(telefono)
        $('#checkout-telefono2').val(telefono)
    })
    $('#checkout-telefono2').keyup(function(){
        var telefono = telefono_format($('#checkout-telefono2').val())
        $('#checkout-telefono').val(telefono)
        $('#checkout-telefono2').val(telefono)
    })
    $('#checkout-cp').change(function(){
        $('#checkout-cp2').val($('#checkout-cp').val())
    })
    $('#checkout-cp2').change(function(){
        $('#checkout-cp').val($('#checkout-cp2').val())
    })
    $('#estado-id').change(function(){
        $('#estado-id-2').val($('#estado-id').val())
    })
    $('#estado-id-2').change(function(){
        $('#estado-id').val($('#estado-id-2').val())
    })


    $('#checkout-cp').keydown(function(e){
        var key = e.charCode || e.keyCode || 0;
        return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105)
        );
    });
    $('#checkout-cp2').keydown(function(e){
        var key = e.charCode || e.keyCode || 0;
        return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105)
        );
    });

    $('#tarjeta-codigo').keydown(function(e){
        var key = e.charCode || e.keyCode || 0;
        return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105)
        );
    });
    $('#tarjeta-codigo2').keydown(function(e){
        var key = e.charCode || e.keyCode || 0;
        return (
            key == 8 ||
            key == 9 ||
            key == 13 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105)
        );
    });

    //Siempre que salgamos de un campo de texto, se chequeará esta función
    $(".checkout-form").keyup(function() {
        if (
            checkCorreo("#checkout-correo", emailPattern) &&
            $("#checkout-nombre").val().length &&
            $("#checkout-apellido").val().length &&
            $("#checkout-calle").val().length &&
            $("#checkout-ciudad").val().length &&
            $("#checkout-telefono").val().length &&
            $("#checkout-cp").val().length &&
            $('#estado-id option:selected').val() != ''
        ){
            $(".btn-continuar-envio").removeAttr("disabled");
            $(".btn-continuar-envio").removeClass("btn-disabled");
        } else {
            $(".btn-continuar-envio").addClass("btn-disabled");
        }
    });

    $(".envios-radio").click(function(){
//        $('input[name$="radio-envio"]').css("border-color", "lime");
        if($('.envio-standard').is(':checked')){
            $(".btn-continuar-pago").removeAttr("disabled");
            $(".btn-continuar-pago").removeClass("btn-disabled");
        } else if ($('.envio-express').is(':checked')) {
            $(".btn-continuar-pago").removeAttr("disabled");
            $(".btn-continuar-pago").removeClass("btn-disabled");
        } else {
            $(".btn-continuar-pago").addClass("btn-disabled");
        }
    })

    $('input[name=radio-pago]').click(function(){
//        setTimeout(function(){
            $("input[name=radio-pago2][value="+$('input[name=radio-pago]:checked').val()+"]").prop('checked', true)
//        })
    });
    $('input[name=radio-pago2]').click(function(){
//        setTimeout(function(){
            $("input[name=radio-pago][value="+$('input[name=radio-pago2]:checked').val()+"]").prop('checked', true)
//        })
    });

    $(".pago-radio").click(function(){
//        $('input[name$="radio-envio"]').css("border-color", "lime");
        if($('.radio-tarjeta').is(':checked')){
            $(".btn-realizar-pago").addClass("btn-disabled");
            $(".btn-realizar-pago").attr("disabled", true);
            $('.tarjeta-form').keyup(function(){
                if (
                    $("#tarjeta-num").val().length &&
                    $("#tarjeta-titular").val().length &&
                    $("#tarjeta-vencimiento").val().length &&
                    $("#tarjeta-codigo").val().length &&
                    $('.check-terminos').is(':checked')
                ) {
                    $(".btn-realizar-pago").removeAttr("disabled");
                    $(".btn-realizar-pago").removeClass("btn-disabled");
                } else {
                    $(".btn-realizar-pago").addClass("btn-disabled");
                }
            })

        } else if ($('.radio-efectivo').is(':checked') && $('.check-terminos').is(':checked')) {
            $(".btn-realizar-pago").removeAttr("disabled");
            $(".btn-realizar-pago").removeClass("btn-disabled");
        } else if ($('.radio-paypal').is(':checked') && $('.check-terminos').is(':checked')){
            $(".btn-realizar-pago").removeAttr("disabled");
            $(".btn-realizar-pago").removeClass("btn-disabled");
        } else {
            $(".btn-realizar-pago").addClass("btn-disabled");
            $(".btn-realizar-pago").attr("disabled", true);
        }
    })

    $("#check-terminos").click(function(){
        if ($('#check-terminos').is(':checked')){
            if($('.radio-tarjeta').is(':checked')){
                if (
                    $("#tarjeta-num").val().length &&
                    $("#tarjeta-titular").val().length &&
                    $("#tarjeta-vencimiento").val().length &&
                    $("#tarjeta-codigo").val().length
                ){
                    $(".btn-realizar-pago").removeAttr("disabled");
                    $(".btn-realizar-pago").removeClass("btn-disabled");
                } else {
                    $(".btn-realizar-pago").addClass("btn-disabled");
                    $(".btn-realizar-pago").attr("disabled", true);
                    $('.tarjeta-form').keyup(function(){
                        if (
                            $("#tarjeta-num").val().length &&
                            $("#tarjeta-titular").val().length &&
                            $("#tarjeta-vencimiento").val().length &&
                            $("#tarjeta-codigo").val().length &&
                            $('.check-terminos').is(':checked')
                        ) {
                            $(".btn-realizar-pago").removeAttr("disabled");
                            $(".btn-realizar-pago").removeClass("btn-disabled");
                        } else {
                            $(".btn-realizar-pago").addClass("btn-disabled");
                            $(".btn-realizar-pago").attr("disabled", true);
                        }
                    })
                }
            } else if ($('.radio-efectivo').is(':checked') && $('.check-terminos').is(':checked')) {
                $(".btn-realizar-pago").removeAttr("disabled");
                $(".btn-realizar-pago").removeClass("btn-disabled");
            } else if ($('.radio-paypal').is(':checked') && $('.check-terminos').is(':checked')){
                $(".btn-realizar-pago").removeAttr("disabled");
                $(".btn-realizar-pago").removeClass("btn-disabled");
            } else {
                $(".btn-realizar-pago").addClass("btn-disabled");
                $(".btn-realizar-pago").attr("disabled", true);
            }
        } else{
            $(".btn-realizar-pago").addClass("btn-disabled");
            $(".btn-realizar-pago").attr("disabled", true);
        }
    })
    $("#check-terminos2").click(function(){
        if ($('#check-terminos').is(':checked')){
            $(".btn-realizar-pago").addClass("btn-disabled");
            $(".btn-realizar-pago").attr("disabled", true);
        } else{
            if($('.radio-tarjeta').is(':checked')){
                if (
                    $("#tarjeta-num").val().length &&
                    $("#tarjeta-titular").val().length &&
                    $("#tarjeta-vencimiento").val().length &&
                    $("#tarjeta-codigo").val().length
                ){
                    $(".btn-realizar-pago").removeAttr("disabled");
                    $(".btn-realizar-pago").removeClass("btn-disabled");
                } else {
                    $(".btn-realizar-pago").addClass("btn-disabled");
                    $(".btn-realizar-pago").attr("disabled", true);
                    $('.tarjeta-form').keyup(function(){
                        if (
                            $("#tarjeta-num").val().length &&
                            $("#tarjeta-titular").val().length &&
                            $("#tarjeta-vencimiento").val().length &&
                            $("#tarjeta-codigo").val().length &&
                            $('.check-terminos').is(':checked')
                        ) {
                            $(".btn-realizar-pago").removeAttr("disabled");
                            $(".btn-realizar-pago").removeClass("btn-disabled");
                        } else {
                            $(".btn-realizar-pago").addClass("btn-disabled");
                            $(".btn-realizar-pago").attr("disabled", true);
                        }
                    })
                }
            } else if ($('.radio-efectivo').is(':checked') && $('.check-terminos').is(':checked')) {
                $(".btn-realizar-pago").removeAttr("disabled");
                $(".btn-realizar-pago").removeClass("btn-disabled");
            } else if ($('.radio-paypal').is(':checked') && $('.check-terminos').is(':checked')){
                $(".btn-realizar-pago").removeAttr("disabled");
                $(".btn-realizar-pago").removeClass("btn-disabled");
            } else {
                $(".btn-realizar-pago").addClass("btn-disabled");
                $(".btn-realizar-pago").attr("disabled", true);
            }
        }
    })


    $('.iniciar, .button-close-login').on('click', function() {
        $('.login-block').toggleClass('visible');
    })

    $('#editar-usuario').click(function(){
        $('#signupform-nombre').attr('disabled', false);
        $('#signupform-apellidopaterno').attr('disabled', false);
        $('#signupform-apellidomaterno').attr('disabled', false);
        $('.input-resignup-contacto').addClass('editando-usuario');
        $('.btn-actualizar-usuario').show();
        $('.btn-actualizar-usuario').attr('disabled', false);
        $('#editar-usuario').hide();
    })
    $('#editar-cliente').click(function(){
        $('#clientesform-telefono').attr('disabled', false);
        $('#clientesform-calle').attr('disabled', false);
        $('#clientesform-num_ext').attr('disabled', false);
        $('#clientesform-num_int').attr('disabled', false);
        $('#clientesform-cp').attr('disabled', false);
        $('#cliente-pais-id').attr('disabled', false);
        $('#cliente-estado-id').attr('disabled', false);
        $('#clientesform-ciudad').attr('disabled', false);
        $('#clientesform-delegacion').attr('disabled', false);
        $('#clientesform-colonia').attr('disabled', false);

        $('.label-cliente').addClass('editando-label');
        $('.input-resignup-envio').addClass('editando-campo-cliente');
        $('.field-cliente-pais-id').addClass('editando-campo-cliente');
        $('.field-cliente-estado-id').addClass('editando-campo-cliente');

        $('.btn-actualizar-cliente').show();
        $('.btn-actualizar-cliente').attr('disabled', false);

        $('#editar-cliente').hide();
    })
    $('#subtitulo-cliente-contacto').click(function(){
        $('#form-resignup').toggle()
    })
    $('#subtitulo-cliente-envio').click(function(){
        $('#form-resignup2').toggle()
    })
    $('#subtitulo-cliente-pedidos').click(function(){
        $('#libros-todos').toggle()
    })



    if ($('.slideshow-container').length > 0) {
        $('.slideshow-container').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            fade: true,
            prevArrow: "<div class='contenedor-carr'><img src='" + $('#baseUrl').val() + "/images/ControlIzq.png' /><img class='top' src='" + $('#baseUrl').val() + "/images/ControlIzqA.png' /></div><div class='contenedor-carr'></div>",
            nextArrow: "<div class='contenedor2-carr'><img src='" + $('#baseUrl').val() + "/images/ControlDer.png' /><img class='top2' src='" + $('#baseUrl').val() + "/images/ControlDerA.png' /></div><div class='contenedor2-carr'></div>",
            responsive: [
                {
                    breakpoint: 992,
                    settings:{
                       arrows: false,
                        dots: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        fade: true
                    }
                }
            ]
        });
    }

    if ($('.index-vendido-img').length > 0) {
        $('.index-vendido-img').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            prevArrow: "<div class='contenedor'><img src='" + $('#baseUrl').val() + "/images/ControlIzq.png' /><img class='top' src='" + $('#baseUrl').val() + "/images/ControlIzqA.png' /></div><div class='contenedor'></div>",
            nextArrow: "<div class='contenedor2'><img src='" + $('#baseUrl').val() + "/images/ControlDer.png' /><img class='top2' src='" + $('#baseUrl').val() + "/images/ControlDerA.png' /></div><div class='contenedor2'></div>",
            responsive: [
                {
                    breakpoint: 768,
                    settings:{
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                    }
                }
            ]
        });
    }
    if ($('.index-promociones-img').length > 0) {
        $('.index-promociones-img').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            prevArrow: "<div class='contenedor'><img src='" + $('#baseUrl').val() + "/images/ControlIzq.png' /><img class='top' src='" + $('#baseUrl').val() + "/images/ControlIzqA.png' /></div><div class='contenedor'></div>",
            nextArrow: "<div class='contenedor2'><img src='" + $('#baseUrl').val() + "/images/ControlDer.png' /><img class='top2' src='" + $('#baseUrl').val() + "/images/ControlDerA.png' /></div><div class='contenedor2'></div>",
            responsive: [
                {
                    breakpoint: 768,
                    settings:{
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                    }
                }
            ]
        });
    }
    if ($('.index-recomendacion-img').length > 0) {
        $('.index-recomendacion-img').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            prevArrow: "<div class='contenedor'><img src='" + $('#baseUrl').val() + "/images/ControlIzq.png' /><img class='top' src='" + $('#baseUrl').val() + "/images/ControlIzqA.png' /></div><div class='contenedor'></div>",
            nextArrow: "<div class='contenedor2'><img src='" + $('#baseUrl').val() + "/images/ControlDer.png' /><img class='top2' src='" + $('#baseUrl').val() + "/images/ControlDerA.png' /></div><div class='contenedor2'></div>",
            responsive: [
                {
                    breakpoint: 768,
                    settings:{
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                    }
                }
            ]
        });
    }

    if ($('.index-agenda-img').length > 0) {
        $('.index-agenda-img').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            prevArrow: "<div class='contenedor'><img src='" + $('#baseUrl').val() + "/images/ControlIzq.png' /><img class='top' src='" + $('#baseUrl').val() + "/images/ControlIzqA.png' /></div><div class='contenedor'></div>",
            nextArrow: "<div class='contenedor2'><img src='" + $('#baseUrl').val() + "/images/ControlDer.png' /><img class='top2' src='" + $('#baseUrl').val() + "/images/ControlDerA.png' /></div><div class='contenedor2'></div>",
            responsive: [
                {
                    breakpoint: 780,
                    settings:{
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                    }
                },
            ]
        });
    }
    
    if ($('.index-editoriales-img').length > 0) {
        $('.index-editoriales-img').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
//            prevArrow: "<div class='contenedor'><img src='" + $('#baseUrl').val() + "/images/ControlIzq.png' /><img class='top' src='" + $('#baseUrl').val() + "/images/ControlIzqA.png' /></div><div class='contenedor'></div>",
//            nextArrow: "<div class='contenedor2'><img src='" + $('#baseUrl').val() + "/images/ControlDer.png' /><img class='top2' src='" + $('#baseUrl').val() + "/images/ControlDerA.png' /></div><div class='contenedor2'></div>",
            responsive: [
                {
                    breakpoint: 780,
                    settings:{
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                    }
                },
            ]
        });
    }

    if ($('.ver-relacionados-img').length > 0) {
        $('.ver-relacionados-img').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            responsive: [
                {
                    breakpoint: 768,
                    settings:{
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        autoplay: false,
                        autoplaySpeed: 2000,
                    }
                }
            ]
        });
    }
    
    $('#catalogo-mas').click(function(){
        
        var incremento = $('.datos-ajax').data('incremento') + 12;
        
        var params = [];
        var tema = $('.datos-ajax').data('tema');
        var editorial = $('.datos-ajax').data('editorial');
        var buscar = $('.datos-ajax').data('buscar');
        var vendidos = $('.datos-ajax').data('vendidos');
        var promociones = $('.datos-ajax').data('promociones');
        var recomendaciones = $('.datos-ajax').data('recomendaciones');
        var libros = [];
        
        $('.contador-libros').each(function(){
            libros.push($(this).data('libroid')); 
        })
        $.ajax({
            url:'index',
            type: 'post',
            data: {incremento:incremento, tema:tema, editorial:editorial, buscar:buscar, vendidos:vendidos, promociones:promociones, recomendaciones:recomendaciones, libros:libros},
            success: function(respuesta){
                $('.container-ajax').last().append(respuesta);
                if ($('.todo-mostrado').length){
                    $('#catalogo-mas').hide();
                }
            },
        })
    })
    
    
    $('#promociones-mas').click(function(){
        var incremento = $('.datos-ajax').data('incremento') + 12;
        $.ajax({
            url:'index',
            type: 'post',
            data: {incremento:incremento},
            success: function(respuesta){
                $('.container-ajax').replaceWith(respuesta);
                $('.datos-ajax').data('incremento', incremento);
            },
        })
    })

            
        
    function blogAlturaTitulo(midiv) {
        var alto = 0;
        setTimeout(function(){
            midiv.each(function() {
                altura = $(this).height();
                if(altura > alto) {
                    alto = altura;
                }
            });
            midiv.height(alto);
        }, 1000);
    }
    
    function blogAlturaAutor(midiv) {
        var alto = 0;
        midiv.each(function() {
            altura = $(this).height();
            if(altura > alto) {
                alto = altura;
            }
        });
        midiv.height(alto);
    }
    
    function blogAlturaResumen(midiv) {
        
        var alto = 0;
        midiv.each(function() {
            altura = $(this).height();
            if(altura > alto) {
                alto = altura;
            }
        });
        midiv.height(alto);
    }
    
//    function blogAlturaImg(midiv) {
//        var alto = 0;
//        midiv.each(function() {
//            altura = $(this).height();
//            if(altura > alto) {
//                alto = altura;
//            }
//        });
//        midiv.height(alto);
//    }
    
//    blogAlturaImg($('.blog-img-wrap'));
    blogAlturaTitulo($('.blog-titulo-wrap'));
    blogAlturaAutor($('.blog-autor-wrap'));
    blogAlturaResumen($('.blog-resumen-wrap'));
    
    $(window).resize(function(){
        
        $('.blog-img-wrap').css('height','auto');
        $('.blog-titulo-wrap').css('height','auto');
        $('.blog-autor-wrap').css('height','auto');
        $('.blog-resumen-wrap').css('height','auto');
//        $('.solucion-wrap').css('height','auto');
        
//        blogAlturaImg($('.blog-img-wrap'));
        blogAlturaTitulo($('.blog-titulo-wrap'));
        blogAlturaAutor($('.blog-autor-wrap'));
        blogAlturaResumen($('.blog-resumen-wrap'));
//        solucionAltura($('.solucion-wrap'));
        
    })
    
    $('#filtrar-eventos').click(function(){
        var categoria = $('#select-categorias').val();
        var mes =$('#select-meses').val();
        
        var ruta = 'index'
        if(categoria){
            ruta += '?categoria='+categoria;
            if(mes){
                ruta += '&mes='+mes;
            }
        } else {
            if(mes){
                ruta += '?mes='+mes;
            }
        }
        window.location = ruta;
    });
    
    $('.trigger-scale').hover(function(){
//        alert('hov');
        $(this).find('.scalable').toggleClass('scale');
    })
    
}); //fin document ready
