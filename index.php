<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>

    <!--full calendar-->
    <link rel="stylesheet" href="css/fullcalendar.min.css">
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/es.js"></script>

    <!-- Reloj -->

    <script src="js/bootstrap-clockpicker.js"></script>
    <link rel="stylesheet" href="css/bootstrap-clockpicker.css">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


    <style>
        .fc th{
            padding: 10px;
            vertical-align: middle;
            background: #f2f2f2;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-7">
                <br><br>
                <div id="CalendarioWeb"></div>
            </div>
            <div class="col"></div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#CalendarioWeb').fullCalendar({
                header: {
                    left: 'today,prev,next,miBoton',
                    center: 'title',
                    right: 'month,basicWeek,basicDay,agendaWeek,agendaDay'
                },
                customButtons: {
                    miBoton: {
                        text: "Acerca",
                        click: function() {
                            alert("Agrega los días que necesites laboratorio \nSí deseas cancelar, puedes borrarlo.");
                        }
                    }
                },
                dayClick: function(date, jsEvent, view) {
                    // $('#txtFecha').val(date.format())

                    $('#btnAgregar').prop("disabled",false);
                    $('#btnModificar').prop("disabled",true);
                    $('#btnBorrar').prop("disabled",true);

                    clean();
                    $("#txtFecha").val(date.format("Y-MM-DD"));
                    $('#txtHora').val(date.format("HH:mm"));
                    $("#ModalEventos").modal('show');

                },
                events: 'http://localhost/calendar/eventos.php',

                eventClick: function(calEvent, jsEvent, view) {

                    $('#btnAgregar').prop("disabled",true);
                    $('#btnModificar').prop("disabled",false);
                    $('#btnBorrar').prop("disabled",false);

                    //h2
                    $('#tituloEvento').html(calEvent.title);

                    //mostrar la informacion del evento en los input
                    $('#txtDescripcion').val(calEvent.description);
                    $('#txtID').val(calEvent.id);
                    $('#txtTitulo').val(calEvent.title);
                    $('#txtColor').val(calEvent.color);

                    fechaHora = calEvent.start._i.split(" ");
                    $('#txtFecha').val(fechaHora[0]);

                    // FechaHoraf = calEvent.end._i.split(" ");
                    // $('#txt_fechafinal').val(FechaHoraf[0]);
                    // $('#txt_horafinal').val(FechaHoraf[1]);


                    $('#ModalEventos').modal('show');
                },
                editable: true,
                eventDrop: function(calEvent) {
                    $('#txtID').val(calEvent.id);
                    $('#txtTitulo').val(calEvent.title);
                    $('#txtColor').val(calEvent.color);
                    $('#txtDescripcion').val(calEvent.description);

                    var fechaHora = calEvent.start.format().split("T");
                    $('#txtFecha').val(fechaHora[0]);
                    $('#txtHora').val(fechaHora[1]);

                    recolectarDatosGUI();
                    EnviarInformacion('modificar', nuevoEvento, true);
                }

            });
        });
    </script>



    <!-- Moda(Agregar,Modificar,Eliminar) -->
    <div class="modal fade" id="ModalEventos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloEvento"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="txtID" name="txtID">



                    <div class="form-group row">
                        <label for="">Fecha:</label>
                        <div class="form-group col-md-15">
                            <input type="text" id="txtFecha" name="txtFecha" class="form-control">
                        </div>


                        <div class="form-group col-md-8">
                            <label for="">Titulo:</label>
                            <input type="text" id="txtTitulo" class="form-control" placeholder="Titulo del Evento">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Hora Evento:</label>

                            <div class="input-group clockpicker" data-autoclose="true">
                                <!-- -->
                                <input type="text" id="txtHora" value="10:30" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Descripción: </label>
                        <textarea type="text" id="txtDescripcion" rows="3" class="form-control"></textarea>

                        <div class="form-group">
                            <label for="">Color: </label>
                            <input type="color" value="#ff0000" id="txtColor" class="form-control" style="height: 36px;">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="form-group">
                            <label for="">Fecha fin:</label>
                            <input type="text" id="txt_fechafinal" name="txt_fechafinal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Hora final:</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <!-- -->
                                <input type="text" id="txt_horafinal" value="10:30" class="form-control">
                            </div>

                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                    <button type="button" id="btnAgregar" class="btn btn-success">Agregar</button>
                    <button type="button" id="btnModificar" class="btn btn-success">Modificar</button>
                    <button type="button" id="btnBorrar" class="btn btn-danger">Borrar</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var nuevoEvento;
        $('#btnAgregar').click(function() {
            recolectarDatosGUI();
            EnviarInformacion('agregar', nuevoEvento);
        });

        $('#btnBorrar').click(function() {
            recolectarDatosGUI();
            EnviarInformacion('eliminar', nuevoEvento);
        });

        $('#btnModificar').click(function() {
            recolectarDatosGUI();
            EnviarInformacion('modificar', nuevoEvento);
        });

        function recolectarDatosGUI() {
            nuevoEvento = {
                id: $('#txtID').val(),
                title: $('#txtTitulo').val(),
                start: $('#txtFecha').val() + " " + $('#txtHora').val(),
                color: $('#txtColor').val(),
                description: $('#txtDescripcion').val(),
                textColor: '#ffffff',
                end: $('#txt_fechafinal').val() + " " + $('#txt_horafinal').val()

            };
        }


        function EnviarInformacion(accion, objEvento, modal) {
            $.ajax({
                type: 'POST',
                url: 'eventos.php?accion=' + accion,
                data: objEvento,
                success: function(msg) {
                    if (msg) {
                        $('#CalendarioWeb').fullCalendar('refetchEvents');

                        if (!modal) {
                            $('#ModalEventos').modal('toggle');
                        }
                    }

                },
                error: function() {
                    alert("Hay un error..");
                }
            });
        }

        $('.clockpicker').clockpicker();

        function clean() {
            $('#txtID').val("");
            $('#txtTitulo').val("Titulo del Evento Aqui...");
            $('#txtColor').val("");
            $('#txtDescripcion').val("");
        }
    </script>


</body>

</html>