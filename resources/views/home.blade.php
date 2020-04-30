@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Registro de Vehículos</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vehiculoModal">
                        Agregar Vehiculo
                    </button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#subirExcelModal">
                        Subir excel
                    </button>
                    <a href=" {{ route('exportar.automoviles')}}" class="btn btn-success">
                        Descargar excel
                    </a>


                    <!-- Modal -->
                    <div class="modal fade" id="vehiculoModal" tabindex="-1" role="dialog"
                        aria-labelledby="vehiculoModalLabel" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('automovil.store') }}" id="form-automovil"
                                    enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="vehiculoModalLabel">Formulario agregar vehículo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @csrf
                                        <input type="hidden" name="hidden_id" id="hidden_id">
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

                                        <div class="form-row">
                                            <div class="form-group col-md-12">                                                
                                                <img src="{{asset('imagenes/kci-239/ephVKPY2vTBUQuk6RrsC128JjqWUbIbdYJTupOl4.jpeg')}}" alt="" style="width: 150px; height: 100px;">
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
                    <!-- fin Modal -->



                    <!-- Modal -->
                    <div class="modal fade" id="subirExcelModal" tabindex="-1" role="dialog"
                        aria-labelledby="subirExcelModalLabel" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('importar.automoviles') }}" id="form-automovil"
                                    enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="subirExcelModalLabel">Formulario importar excel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @csrf

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="archivo_excel">Importar Excel</label>
                                                <input type="file" class="form-control" name="archivo_excel"
                                                    id="archivo_excel">
                                                <span class="invalid-feedback hide" role="alert"></span>
                                                <div class="valid-feedback">ok!</div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary"
                                            id="btn-guardar-vehiculo">Enviar</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- fin Modal -->


                    <br><br><br>
                    <div class="row">
                        <div class=" col-md-12">

                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Conductor</th>
                                        <th scope="col">Placas</th>
                                        <th scope="col">Modelo</th>
                                        <th scope="col">Valor Ingreso</th>
                                        <th scope="col">Fecha Creación</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Imagen</th>

                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datos as $automovil)
                                    <tr>
                                        <th scope="row">{{ $automovil->id }}</th>
                                        <td>{{ $automovil->conductor }}</td>
                                        <td>{{ $automovil->placas }}</td>
                                        <td>{{ $automovil->modelo }}</td>
                                        <td>{{ $automovil->valor }}</td>
                                        <td>{{ $automovil->created_at }}</td>
                                        <td>{{ $automovil->estado==1?'Activo':'Inactivo' }}</td>
                                    <td><img src="{{asset($automovil->imagen)}}" alt="" style="width: 70px; height: 50px;"></td>
                                        <td>
                                            <button type="button" class="btn btn-success"
                                                onclick="mostrarModalEditar({{$automovil->id}})">
                                                Editar
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                onclick="eliminarVehiculo({{$automovil->id}})">
                                                x
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>

                            {{ $datos->links()}}
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // toastr.error("hola");
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
    $("#" + idForm)[0].reset();
    camposForm = getCamposForm(idForm);

    for (b in camposForm) {
        campo = camposForm[b];
        $campoo = $("#" + campo);
        $campoo.removeClass("is-invalid");
        $campoo.removeClass("is-valid");

        $campoo
            .siblings("span")
            .removeClass("show")
            .addClass("hide")
            .text("Campo obligatorio");
    }
}

$("#btn-guardar-vehiculo").click(function() {
    var $imagen = $("#imagen");
    var formData = new FormData($("#form-automovil")[0]);
    formData.append("imagen", $imagen[0].files[0]);
    var data = $("#form-automovil").serialize();
    var camposForm = getCamposForm("form-automovil");
    var url = $("#form-automovil").attr("action");

    $.ajax({
        async: true,
        cache: false,
        type: "POST",
        url: url + "?" + data,
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
           
        }
    })
        .done(function(reply) {
            if (reply.created == false) {
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
                limpiarFormulario("form-automovil");
                $("#vehiculoModal").modal("hide");
                toastr.success("Vehículo guardado exitosamente");
                setTimeout(() => {
                    window.location.href = base_path + "/home";
                }, 1000);
            }
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert("El servidor no responde");
        });
});

function eliminarVehiculo(id) {
    $.ajax({
        async: true,
        cache: false,
        type: "DELETE",
        url: base_path + "/automovil/" + id,
        data: { _token: "{{ csrf_token() }}" },
        beforeSend: function() {
            console.log("cargando...");
        }
    })
        .done(function(res) {
            if (res.deleted == true) {
                toastr.success("Registro eliminado");
                window.location.href = base_path + "/home";
            }

            if (res.deleted == false) {
                toastr.error(res.details);
            }
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            toastr.error("El servidor no responde");
        });
}

function mostrarModalEditar(id){

    $.ajax({
        async: true,
        cache: false,
        type: "GET",
        url: base_path + "/automovil/" + id,
        beforeSend: function() {
            console.log("cargando...");
        }
    })
        .done(function(resp) {

            console.log(resp);
            for (i in resp) {               
                 if($("#" + i).length >0){
                  $("#" + i).val(resp[i]);
                 }              
              }            
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            toastr.error("El servidor no responde");
        });

    $("#vehiculoModal").modal("show");
    $("#hidden_id").val(id);
}

</script>
@endsection