@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Registro de Vehículos</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Agregar Vehiculo
                    </button>
                    <button type="button" class="btn btn-success">
                        Subir excel
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('automovil.store') }}" id="form-automovil"
                                    enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Formulario agregar vehículo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="conductor">Conductor *</label>
                                                <input type="text" class="form-control" name="conductor" id="conductor">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>


                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="placas">Placas *</label>
                                                <input type="text" class="form-control" name="placas" id="placas">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="modelo">Modelo *</label>
                                                <input type="text" class="form-control" name="modelo" id="modelo">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>


                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12 ">
                                                <label for="observacion">Observación</label>
                                                <textarea class="form-control" name="observacion" id="observacion"
                                                    placeholder="Observación"></textarea>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="imagen">Subir imagen</label>
                                                <input type="file" class="form-control" name="imagen" id="imagen">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary"
                                            id="btn-guardar-vehiculo">Guardar</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getCamposForm(idForm) {
        var camposForm = [];
        $("#" + idForm)
            .find("input[type='text'],input[type='number'],file,select,textarea")
            .each(function() {
                if ($(this).attr("name")) {
                    camposForm.push($(this).attr("name"));
                }
            });
        return camposForm;
    }

    function limpiarFormulario(idForm) {
                $('#'+idForm)[0].reset();
                camposForm=getCamposForm(idForm);


                for (b in camposForm) {
                    
                    campo=camposForm[b];
                    $campoo=$('#'+campo);
                    $campoo.removeClass("is-invalid")
                    $campoo.removeClass("is-valid");

                        $campoo.siblings("span")
                        .removeClass("show")
                        .addClass("hide")
                        .text('Campo obligatorio');

                }

            }


    $("#btn-guardar-vehiculo").click(function(){
        var $imagen=$('#imagen');
        var formData = new FormData($('#form-automovil')[0]);        
        formData.append('imagen',$imagen[0].files[0]);
        var data = $('#form-automovil').serialize();
        var camposForm = getCamposForm('form-automovil');
        var url = $('#form-automovil').attr('action');
       

        $.ajax({
            async: true,
            cache: false,
            type: 'POST',
            url: url+'?'+data,
            data:formData ,
            contentType: false,
            processData: false,
            beforeSend: function() {

                // $('#btn-enviar-contacto').removeClass('d-block').addClass('d-none');
                // $('#btn-loading-contacto').removeClass('d-none').addClass('d-block');
            }
        }).done(function(res) {


            // $('#btn-enviar-contacto').removeClass('d-none').addClass('d-block');
            // $('#btn-loading-contacto').removeClass('d-block').addClass('d-none');

reply = res;
console.log('========prueba=====');
console.log(reply);


if (reply.created == false) {
    // toastr.error("Por favor revisar el formulario");
    // alert("Por favor revisar el formulario");

    // $('#message-success-contacto').removeClass('d-block').addClass('d-none');
    // $('#message-alert-contacto').removeClass('d-none').addClass('d-block');
    var camposError = [];
    errors = reply.errors;

    for (a in errors) {
        campo = errors[a];
        for (b in campo) {
            campo2 = campo[b];
            camposError.push(b);

            $("#" + b)
                .removeClass("is-invalid")
                .addClass("is-invalid");
            $("#" + b)
                .siblings("span")
                .removeClass("hide")
                .addClass("show")
                .text(campo[b]);
        }
    }

    var camposOk = [];

    for (c in camposForm) {
        if (camposError.indexOf(camposForm[c]) == -1) {
            camposOk.push(camposForm[c]);
        }
    }

    for (d in camposOk) {
        $("#" + camposOk[d])
            .removeClass("is-invalid")
            .addClass("is-valid");
        $("#" + camposOk[d])
            .siblings("span")
            .removeClass("show")
            .addClass("hide")
            .text("");
    }

    
}

if (reply.created == true) {

    limpiarFormulario('form-automovil');
    // for (e in camposForm) {
    //     $("#" + camposForm[e])
    //         .removeClass("is-invalid")
    //         .addClass("is-valid");
    //     $("#" + camposForm[e])
    //         .siblings("span")
    //         .removeClass("show")
    //         .addClass("hide")
    //         .text("");
    // }

    // $('#message-success-contacto').removeClass('d-none').addClass('d-block');
    // $('#message-alert-contacto').removeClass('d-block').addClass('d-none');
    
    // clearForm('form-automovil');
    // toastr.success("Dato guardado correctamente");

    // $("#formModal").modal('hide');

    
}
})
.fail(function(jqXHR, ajaxOptions, thrownError) {
alert("El servidor no responde");
});
    });
    
    // alert($('#nombre').val() );
</script>
@endsection