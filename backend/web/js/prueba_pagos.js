$(document).ready(function(){

if ($('#baseurl').val() == 'http://www.uppl.blackrobot.mx'){
    document.domain = 'blackrobot.mx';
} else if ($('#baseurl').val() != 'http://localhost/uppl/backend/web'){
    document.domain = 'unpaseoporloslibros.com';    
}
    
    $('.prueba-alerta').click(function(){
//        setTimeout(function(){
        var nombre = '';
        var llave_publica = '';

        var numero = '';
        var codigo = '';
        var mes = '';
        var anio = '';
        
        nombre = $('#copy-nombre').val();
        llave_publica = $('#copy-key').val();

        numero = $('#copy-tarjeta').val();
        codigo = $('#copy-seguridad').val();
        mes = $('#copy-mes').val();
        anio = $('#copy-anio').val();

        if(!Conekta.card.validateNumber(numero)){
            return;
        }
        if (Conekta.card.getBrand(numero) == 'amex'){
            return;                
        }
        if(!Conekta.card.validateCVC(codigo)){
            return;
        }
        if(!Conekta.card.validateExpirationDate(mes,anio)){
            return;
        }
        Conekta.setPublicKey(llave_publica);
        var tokenParams = {
            "card": {
                "number": numero,
                "name": nombre,
                "exp_year": anio,
                "exp_month": mes,
                "cvc": codigo
            }
        };
        Conekta.Token.create(tokenParams, function(token) {

            var data = [];
            var cupon = {};
            var pago = {};

            cupon["id"] = $('#copy-cupon').val();
            cupon["porcentaje"] = $('#copy-porcentaje').val();
            cupon["descuento"] = $('#copy-descuento').val();

            pago["tipo"] = 'tarjeta';
            pago["total"] = $('#copy-subtotal').val();
            pago["editorial"] = $("#copy-editorial-id").val();
            pago["carrito"] = $("#copy-carrito").val();
            pago["calle"] = $("#copy-calle").val();
            pago["cp"] = $("#copy-cp").val();
            pago["nombre"] = $("#copy-nombre").val();
            pago["email"] = $("#copy-email").val();
            pago["telefono"] = $("#copy-telefono").val();
            data.push(cupon);
            data.push(pago);
            data.push(token);
            $.ajax({
                url: "datos",
                type: 'post',
                data: {token: token, cupon:cupon, pago:pago},
                dataType: "html",
                success: function (respuesta) {

                    var resp = JSON.parse(respuesta);
                    var mensaje = resp.mensaje;
                    var exito = resp.exito;
                    
                    if(exito == '0'){
                        $('#copy-exito').val(exito)

                    } else {    
                        $('#copy-order').val(resp.order_id);
                        $('#copy-monto').val(resp.order_monto);
                        $('#copy-codigo').val(resp.order_codigo);
                        $('#copy-numeros').val(resp.order_numeros);
                        $('#copy-marca').val(resp.order_marca);
                        $('#copy-tipo').val(resp.order_tipo);
                        $('#copy-exito').val(exito);
                    }
                },
           });

        });
    })
})