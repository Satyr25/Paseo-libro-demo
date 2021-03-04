
$('#descuentos-todos').on('click', '.visualDatos', function() {
    var id1 = $(this).parent().attr('id');
    var dict = {};
    dict.id = id1;
    $.ajax({
        url: "ver",
        type: "post",
        data: dict,
        success: function (data) {
            if(data){
                $('#modal-ver-promos').modal('show');
                $('#ver-promo-placeholder').html(data);
            }
            else{
                alert("Error mostrando libro")
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });
});

$('#promo_lib_cod').on('click', '.datos', function () {
    var id1 = $(this).parent().attr('id');
    var dict = {};
    dict.id = id1;
    $.ajax({
        url: "detallepromo/mostrar",
        type: "get",
        data: dict,
        success: function (data) {
            if(data){
                $('#modal-ver-promos-libro').modal('show');
                $('#ver-promo-libro-placeholder').html(data);
            }
            else{
                alert("Error mostrando libro")
            }
        },
        error: function () {
            alert("Something went wrong");
        }
    });
 
});

$('#inicio-libros').on('click', '#boton-actualizar-libros', function() {
    $('#libros-update-form').on('beforeSubmit', function(e) {
    $('#boton-actualizar-libros').attr('disabled', true);
    $('.loader').css("display","flex");
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: "actualizar",
        type: "post",
        data: formData,
        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
        processData: false, // NEEDED, DON'T OMIT this
        
        success: function (data) {
            // if(data){
            //     alert('error');
            // }
                $('.loader').css("display","none");

                // escondo el modal de libro
                $('#modal-ver-libros').modal('hide');

                // muestro el alert de exito
                alert('La información se editó correctamente');
                location.reload();
                
                // $('div.alert-placeholder').html(
                // '<div class="alert alert-success alert-dismissible coloralert"><a href="#" class="close" data-dismiss="alert">x</a>Libro editado exitosamente</div>');

            //     //recargo la tabla de libro
            //     refreshTableDataLibro();
            // }
            // else{
            //     $('.loader').css("display","none");
            //     $('div.alert-placeholder').html('<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert">x</a>Error editando libro</div>');
            // }
        },
        error: function () {
            $('.loader').css("display","none");
            alert("Something went wrong");
        }
    });
}).on('submit', function(e){
    e.preventDefault();
});
});




$('#agregar-lib').on('click', '.botonBuscar', function() {
    $('#w3').on('beforeSubmit', function(e) {
    var titulo = $('#librossearch-titulo').val();
    var id = $('#add-lib').data('promo');
    window.location.replace("../agregar/index?codigo="+id+"&buscar="+titulo);
}).on('submit', function(e){
    e.preventDefault();
});
});

$('body').on('click', '#boton-actualizar-precio', function() {
    $('#libros-update-form').on('beforeSubmit', function(e) {
        $('#boton-actualizar-libros').attr('disabled', true);  
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "detallepromo/actualizar",
            type: "post",
            data: formData,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT this
            
            success: function (data) {
                alert('El precio de promoción se edito correctamente');
                $('#modal-ver-promos-libro').modal('hide');
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });
});

$('#modal-ver-promos').on('click', '#boton-desc-crear', function() {
    $('#crear-descuento-form').on('beforeSubmit', function(e) {
        var nuevo = 1;
        var des_nuevo_glob = 0;
        if( $('#crear-descuento-form').find('#descuentosform-global').prop('checked') ) {
            des_nuevo_glob = 1;
        }
        $.ajax({
            url: "globaldes",
            type: "post",
            data: {nuevo:nuevo, des_nuevo_glob:des_nuevo_glob},
            
            success: function (data) {
                if(data){
                    var codigo = $('#crear-descuento-form').find("#descuentosform-codigo").val();
                    var porcentaje = $('#crear-descuento-form').find("#descuentosform-porcentaje").val();
                    var activo = 0;
                    var cupon_glob = 0;
                    if( $('#descuentosform-activo').prop('checked') ) {
                        activo = 1;
                    }
                    if( $('#descuentosform-global').prop('checked') ) {
                        cupon_glob = 1;
                    }
                    $.ajax({
                        url: "creardesc",
                        type: "post",
                        data: {codigo:codigo, porcentaje:porcentaje, activo:activo, cupon_glob:cupon_glob},
                        
                        success: function (data) {
                            alert('Se creo el descuento correctamente');
                            location.reload();
                        },
                        error: function () {
                            alert("Something went wrong");
                        }
                    });
                }else{
                    alert('Ya existe un cupón Global');
                    var codigo = $('#crear-descuento-form').find("#descuentosform-codigo").val();
                    var porcentaje = $('#crear-descuento-form').find("#descuentosform-porcentaje").val();
                    var activo = 0;
                    var cupon_glob = 0;
                    if( $('#descuentosform-activo').prop('checked') ) {
                        activo = 1;
                    }
                    $.ajax({
                        url: "creardesc",
                        type: "post",
                        data: {codigo:codigo, porcentaje:porcentaje, activo:activo, cupon_glob:cupon_glob},
                        
                        success: function (data) {
                            alert('Se creo el descuento correctamente');
                            location.reload();
                        },
                        error: function () {
                            alert("Something went wrong");
                        }
                    });
                }
            },
            error: function () {
                alert("Something went wrong");
            }
        });

    }).on('submit', function(e){
        e.preventDefault();
    });
});

$('#modal-ver-promos').on('click', '#boton-editar', function() {
    $('#editar-promo-form').on('beforeSubmit', function(e) {
        var codigo = $('#codingPromo').data('promo');
        var renombrar = $('#editar-promo-form').find("#promocionform-codigo").val();
        $.ajax({
            url: "editarpromo",
            type: "post",
            data: {codigo:codigo, renombrar:renombrar},
            
            success: function (data) {
                alert('Se renombró la promoción correctamente');
                refreshTablePromos();
                window.location.reload();
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });
});

$('#modal-ver-promos').on('click', '#boton-crear', function() {
    $('#crear-promo-form').on('beforeSubmit', function(e) {
        var codigo = $('#crear-promo-form').find("#promocionform-codigo").val();
        $.ajax({
            url: "crearpromo",
            type: "post",
            data: {codigo:codigo},
            
            success: function (data) {
                alert('Se creó la promoción correctamente');
                refreshTablePromos();
                window.location.reload();
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });
});

$('#modal-ver-promos').on('click', '#boton-desc-editar', function() {
    $('#crear-descuento-form').on('beforeSubmit', function(e) {
        var desc_id = $('#descuento-id').val();
        $.ajax({
            url: "globaldes",
            type: "post",
            data: {desc_id:desc_id},
            success: function (data) {
                if(data){
                    var codigo = $('#crear-descuento-form').find("#descuentos-codigo").val();
                    var porcentaje = $('#crear-descuento-form').find("#descuentos-porcentaje").val();
                    var activo = 0;
                    var cupon_glob = 0;
                    if( $('#descuentos-activo').prop('checked') ) {
                        activo = 1;
                    }
                    if( $('#descuentos-global').prop('checked') ) {
                        cupon_glob = 1;
                    }
                    $.ajax({
                        url: "editardesc",
                        type: "post",
                        data: {codigo:codigo, porcentaje:porcentaje, activo:activo, desc_id:desc_id, cupon_glob:cupon_glob},
                        
                        success: function (data) {
                            alert('Se editó el descuento correctamente');
                            location.reload();
                        },
                        error: function () {
                            alert("Something went wrong");
                        }
                    });
                }else{
                    alert('Ya existe un cupón Global');
                    var codigo = $('#crear-descuento-form').find("#descuentos-codigo").val();
                    var porcentaje = $('#crear-descuento-form').find("#descuentos-porcentaje").val();
                    var activo = 0;
                    var cupon_glob = 0;
                    if( $('#descuentos-activo').prop('checked') ) {
                        activo = 1;
                    }
                    $.ajax({
                        url: "editardesc",
                        type: "post",
                        data: {codigo:codigo, porcentaje:porcentaje, activo:activo, desc_id:desc_id, cupon_glob:cupon_glob},
                        
                        success: function (data) {
                            alert('Se editó el descuento correctamente');
                            location.reload();
                        },
                        error: function () {
                            alert("Something went wrong");
                        }
                    });
                }
                
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });
});

$('body').on('click', 'button.boton-editar', function() {
    if($('.muestra-libro').length){
        $('.muestra-libro').toggleClass("oculto");
        $('.edita-libro').toggleClass("oculto");
    }
});

$('body').on('click', '#desactiv', function() { 
    $(location).attr('href', 'index?LibrosSearch[desactivados]=1');
});

$('body').on('click', '#promo', function() { 
    $(location).attr('href', 'index?LibrosSearch[promociones]=1');
});

$('body').on('click', '#activarLibros', function() { 
    $(location).attr('href', 'index?LibrosSearch[activados]=1');
});

$('body').on('click', '#add-lib', function() {
    var url = new URL(document.URL);
    var search_params = new URLSearchParams(url.search);
    var page = 'page';
    if(search_params.has(page)){
        var url_add = "agregarpromo";
    }else{
        var url_add = "agregarpromo";
    }

    var keys = $('#agregar-lib').yiiGridView('getSelectedRows');
    var codigo = $(this).data('promo');
    if(keys==""){
        alert('No se ha seleccionado ningún elemento para agregar');
    }else{
        var add = [];
        $.each($("input[name='selection[]']:checked"), function(){
            add.push($(this).val());
        });
        var jsonString = JSON.stringify(add);
        $.ajax({
            type: "POST",
            url: url_add,
            data: {data : jsonString, codigo:codigo},
            cache: false,
            success: function(response){   
                alert('Libros Agregados');
                refreshTableLibrosPromo();
            }
        });
    }
});

$('body').on('click', '#Eliminar', function() {
    var keys = $('#libros-todos').yiiGridView('getSelectedRows');
    
    if(keys==""){
            alert('No se ha seleccionado ningún elemento para desactivar');
    }else{
        var condicion = confirm("¿Realmente desea desactivar los libros?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "desactivar",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    refreshTableDataLibro();
                    alert('Libros Desactivados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#Activar', function() {
    var keys = $('#libros-todos').yiiGridView('getSelectedRows');

    if(keys==""){
            alert('No se ha seleccionado ningún elemento para Activar');
    }else{
        var condicion = confirm("¿Realmente desea activar los libros?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "activar",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    //if request if made successfully then the response represent the data
                //alert(response);
                    $.each($("input[name='selection[]']:checked"), function(){

                        if(response.includes($(this).val()))
                        {
//                            $(this).parents("tr").remove();
                            refreshTableDataLibro();
                            

                        }

                    });

                    alert('Libros Activados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});


$('body').on('click', '#Eliminar-novedad', function() {
    var keys = $('#libros-todos').yiiGridView('getSelectedRows');
    
    if(keys==""){
            alert('No se ha seleccionado ningún elemento para desactivar');
    }else{
        var condicion = confirm("¿Realmente desea desactivar los libros como novedad?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "desactivar-novedad",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    refreshTableDataLibro();
                    alert('Libros Desactivados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#Activar-novedad', function() {
    var keys = $('#libros-todos').yiiGridView('getSelectedRows');

    if(keys==""){
            alert('No se ha seleccionado ningún elemento para Activar');
    }else{
        var condicion = confirm("¿Realmente desea activar los libros como novedad?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "activar-novedad",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    //if request if made successfully then the response represent the data
                //alert(response);
                    $.each($("input[name='selection[]']:checked"), function(){

                        if(response.includes($(this).val()))
                        {
//                            $(this).parents("tr").remove();
                            refreshTableDataLibro();
                            

                        }

                    });

                    alert('Libros Activados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#Eliminar-recomendacion', function() {
    var keys = $('#libros-todos').yiiGridView('getSelectedRows');
    
    if(keys==""){
            alert('No se ha seleccionado ningún elemento para desactivar recomendación');
    }else{
        var condicion = confirm("¿Realmente desea desactivar los libros como recomendación del mes?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "desactivar-recomendacion",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    refreshTableDataLibro();
                    alert('Libros Desactivados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#Activar-recomendacion', function() {
    var keys = $('#libros-todos').yiiGridView('getSelectedRows');

    if(keys==""){
            alert('No se ha seleccionado ningún elemento para activar recomendación');
    }else{
        var condicion = confirm("¿Realmente desea activar los libros como recomendación del mes?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "activar-recomendacion",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    $.each($("input[name='selection[]']:checked"), function(){
                        if(response.includes($(this).val()))
                        {
                            refreshTableDataLibro();
                        }
                    });
                    alert('Libros Activados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#eliminar-editorial', function() {
    var keys = $('#editoriales-todos').yiiGridView('getSelectedRows');
    
    if(keys==""){
            alert('No se ha seleccionado ningún elemento para desactivar');
    }else{
        var condicion = confirm("¿Realmente desea desactivar las editoriales?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "desactivar",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    alert('Editoriales Desactivados');
                    refreshTableDataEditorial();
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#activar-editorial', function() {
    var keys = $('#editoriales-todos').yiiGridView('getSelectedRows');

    if(keys==""){
            alert('No se ha seleccionado ningún elemento para Activar');
    }else{
        var condicion = confirm("¿Realmente desea activar las editoriales?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
                //alert(favorite);
                //$(this).parents("tr").remove();
            });
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "activar",
                data: {data : jsonString},
                cache: false,
                success: function(response){
                    refreshTableDataEditorial();
                    alert('Editoriales Activadas');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#activar-evento', function() {
    var keys = $('#eventos-todos').yiiGridView('getSelectedRows');

    if(keys==""){
            alert('No se ha seleccionado ningún elemento para Activar');
    }else{
        var condicion = confirm("¿Realmente desea activar los eventos?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
            });
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "activar",
                data: {data : jsonString},
                cache: false,
                success: function(){
                    refreshTableDataEventos();
                    alert('Eventos Activados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});
$('body').on('click', '#eliminar-evento', function() {
    var keys = $('#eventos-todos').yiiGridView('getSelectedRows');

    if(keys==""){
            alert('No se ha seleccionado ningún elemento para Activar');
    }else{
        var condicion = confirm("¿Realmente desea activar los eventos?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
            });
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "desactivar",
                data: {data : jsonString},
                cache: false,
                success: function(){
                    refreshTableDataEventos();
                    alert('Eventos Activados');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#promo-crear', function() {
        $.ajax({
            url: "promo",
            success: function(data){
                $('#modal-ver-promos').modal('show');
                $('#ver-promo-placeholder').html(data);
            }
        });
});

$('body').on('click', '#desc-crear', function() {
        $.ajax({
            url: "desc",
            success: function(data){
                $('#modal-ver-promos').modal('show');
                $('#ver-promo-placeholder').html(data);
            }
        });
});

$('body').on('click', '.renombrar', function() {
        var promo_code = $(this).data('edit');
        $.ajax({
            url: "editpromo",
            type: 'post',
            data:{promo_code:promo_code},
            success: function(data){
                $('#modal-ver-promos').modal('show');
                $('#ver-promo-placeholder').html(data);
            }
        });
});

$('body').on('click', '#promo-eliminar', function() {
    var keys = $('#promocion-todos').yiiGridView('getSelectedRows');

    if(keys==""){
        alert('No se ha seleccionado ningún elemento para eliminar');
    }else{
        var condicion = confirm("¿Realmente desea eliminar la Promoción?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
            });
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "eliminar",
                data: {data : jsonString},
                cache: false,
                success: function(data){
                    $.each($("input[name='selection[]']:checked"), function(){
                        if(data.includes($(this).val()))
                        {
                            $(this).parents("tr").remove();
                            refreshTablePromos();
                        }
                    });
                    alert('Promocion Eliminada');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#desc-eliminar', function() {
    var keys = $('#descuentos-todos').yiiGridView('getSelectedRows');

    if(keys==""){
        alert('No se ha seleccionado ningún elemento para eliminar');
    }else{
        var condicion = confirm("¿Realmente desea eliminar el descuento?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
            });
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "eliminar",
                data: {data : jsonString},
                cache: false,
                success: function(data){
                    $.each($("input[name='selection[]']:checked"), function(){
                        if(data.includes($(this).val()))
                        {
                            $(this).parents("tr").remove();
                            refreshTableDesc();
                        }
                    });
                    alert('Descuento Eliminado');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

$('body').on('click', '#quitar-libros-promo', function() {
    var keys = $('#libros-promo').yiiGridView('getSelectedRows');
    var promo = $('#quitar-libros-promo').data('promo');
    if(keys==""){
        alert('No se ha seleccionado ningún elemento para eliminar de la promoción');
    }else{
        var condicion = confirm("¿Realmente desea eliminas los libros de la Promoción?");
        if(condicion){
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function(){
                favorite.push($(this).val());
            });
            var jsonString = JSON.stringify(favorite);
            $.ajax({
                type: "POST",
                url: "detallepromo/quitar",
                data: {data : jsonString, promo:promo},
                cache: false,
                success: function(data){
                    $.each($("input[name='selection[]']:checked"), function(){
                        if(data.includes($(this).val()))
                        {
                            $(this).parents("tr").remove();
                            refreshTablePromos();
                        }
                    });
                    alert('Libros eliminados de la Promoción');
                }
            });
        }else{
            alert('No se hizo ningún cambio');
        }
    }
});

// $('body').on('click', '#agregar-libros', function() {
//     var url = new URL(document.URL);
//     var search_params = new URLSearchParams(url.search);
//     var page = 'page';
//     var id = $(this).data('promo');
//     if(search_params.has(page)){
//         var url_add = "../detallepromo/agregar";
//     }else{
//         var url_add = "detallepromo/agregar";
//     }
//         $.ajax({
//             url: url_add,
//             type: "post",
//             data:{id:id},
//             success: function(data){
//                 $('#modal-agregar').modal('show');
//                 $('#agregar-lib-add').html(data);
//             }
//         });
// });

function refreshTableDataLibro(){
    $.pjax.reload('#pjax-detalles')
}
function refreshTableDataEditorial(){
    $.pjax.reload('#pjax-editoriales')
}
function refreshTableDataEventos(){
    $.pjax.reload('#pjax-eventos')
}

function refreshTableLibrosPromo(){
    $.pjax.reload('#pjax-grid-promocio-lib')
}

function refreshTablePromos(){
    $.pjax.reload('#pjax-grid-promocion')
}

function refreshTableDesc(){
    $.pjax.reload('#pjax-grid-descuentos')
}

function refreshTableDataLibro2(){
    $.pjax.reload('#pjax-grid-libros-hide')
}

function detalle(data){
    window.location.replace("../detallepromo?codigo="+data);
}

function agregar(data){
    window.location.replace("agregar/index?codigo="+data);
}


$('body').on('click', '#imgdivf', function() {
    $('#uploadform-imagefilef').trigger('click');
});

$('body').on('click', '#imgdivp', function() {
    $('#uploadform-imagefilep').trigger('click');
});

var loadFilep = function(event) {
    var output = document.getElementById('img_portada');
    output.src = URL.createObjectURL(event.target.files[0]);
};

var loadFilef = function(event) {
    var output = document.getElementById('img_fondo');
    output.src = URL.createObjectURL(event.target.files[0]);
};

$( document ).ready(function() {
    
//    document.domain = "http://www.uppl.blackrobot.mx/";
    
    var url = new URL(document.URL);
    var search_params = new URLSearchParams(url.search);
    var desactivados = 'LibrosSearch[desactivados]';
    var activados = 'LibrosSearch[activados]';
    var titulo = 'LibrosSearch[titulo]';
    var codigo = 'codigo';
    if(search_params.has(desactivados)){
        $('#desactiv').text('Mostrar activos');
        $('#desactiv').prop('id', 'activarLibros');
        $('#librossearch-activados').attr('name', 'LibrosSearch[desactivados]');
    }else if (search_params.has(activados)){
        $('#activarLibros').text('Mostrar Desactivados');
        $('#activarLibros').prop('id', 'desactiv');
        $('#librossearch-activados').attr('name', 'LibrosSearch[activados]');
    }
    if(search_params.has(codigo)){
        $('#w0').css('display', 'block');
    }
    
    $('#libro-portada-click').click(function(){
        $('#libro-portada-select').trigger('click')
    });
    
    $('#libro-fondo-click').click(function(){
        $('#libro-fondo-select').trigger('click')
    });
    
    $('#libro-portada-select').on('change', function(){
        if (document.getElementById('libro-portada-select').files && document.getElementById('libro-portada-select').files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#muestra-libro-fondo').css('background-image', 'url('+e.target.result+')');
            }
            reader.readAsDataURL(document.getElementById('libro-portada-select').files[0]);
            $('#editar-portada').hide();
        }
    });    
    
    $('#libro-fondo-select').on('change', function(){
        if (document.getElementById('libro-fondo-select').files && document.getElementById('libro-fondo-select').files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#muestra-libro-fondo-fondo').css('background-image', 'url('+e.target.result+')');
            }
            reader.readAsDataURL(document.getElementById('libro-fondo-select').files[0]);
            $('#editar-fondo').hide();
        }
    });    
    
    $('#evento-imagen-click').click(function(){
        $('#evento-imagen-select').trigger('click')
    });
    
    $('#evento-imagen-select').on('change', function(){
        if (document.getElementById('evento-imagen-select').files && document.getElementById('evento-imagen-select').files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#evento-imagen-mostrar').css('background-image', 'url('+e.target.result+')');
            }
            reader.readAsDataURL(document.getElementById('evento-imagen-select').files[0]);
        }
    });
    
    $('.bootstrap-timepicker').click(function(){
        $('bootstrap-timepicker-widget').addClass('open')
    });
    
    $('.import-button').on('click', function() {
        $('.import-block').slideToggle();
    });
    
    $('#ventas-consultar').click(function(){
//        var inicio = parseInt((new Date($('#ventas-rango').val()).getTime() / 1000).toFixed(0));
        var inicio = $('#ventas-rango').val();
        var fin = $('#ventas-rango-2').val();
        var editorial_id = $('.editoriales-drop').val();
        var ruta = '?PedidoSearch%5Binicio%5D='+inicio+'&PedidoSearch%5Bfin%5D='+fin;
        if (editorial_id){
            ruta += '&PedidoSearch%5Bid%5D='+editorial_id;
        }
        window.location = "index"+ruta;
    })
    
    $('#import-books-form').submit(function(){
        $('.importar-xls').attr('disable');
        $('.lds-facebook').css('display', 'inline-block');
    })
    
    $('#libros-todos').on('click', '.visualDatos', function(event) {
        var id = $(this).attr('id');
        window.location.href = "ver?id=" + id
    })
    $('#editoriales-todos').on('click', '.visualDatos', function(event) {
        var id = $(this).attr('id');
        window.location.href = "ver?id=" + id
    })
    $('#eventos-todos').on('click', '.visualDatos', function(event) {
        var id = $(this).attr('id');
        window.location.href = "ver?id=" + id
    })
    
    $('#marcar-novedad').click(function(){
        var id = $('#marcar-novedad').data('id');
        $.ajax({
            url: 'marcar-novedad',
            type: 'post',
            data: {id:id},
            success: function (respuesta){
                alert(respuesta.mensaje);
                if (respuesta.exito == 1){
                    $('.texto-novedad').text('Este libro esta marcado como una novedad');
                }
            },
            error: function(){
                alert('algo salio mal');
            }
        });
    });
    
    $('.kv-field-separator').text('al');
    
    $('.krajee-datepicker').click(function(){
        if ($('#ruta').data('controlador') == 'eventos'){
            $('.datepicker').css('z-index', '10002');
        }
    })
    
}); // fin de document ready


