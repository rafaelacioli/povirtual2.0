<?php
session_start();
// include("../../Controller/unitlist.php");
// include("../../Controller/typeunitlist.php");
?>

<!DOCTYPE html>
<html lang="pt_br">

<head>
    <title>PO Virtual | Unidades</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="../../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Mapbox -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js"></script>

    <style>
        #map {
            overflow: hidden;
            position: relative;
            height: 0;
        }
    </style>
</head>

<body>

    <style>
        .coordinates {
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            position: absolute;
            bottom: 40px;
            left: 10px;
            padding: 5px 10px;
            margin: 0;
            font-size: 11px;
            line-height: 18px;
            border-radius: 3px;
            display: none;
        }
    </style>

    <div class="wrapper">
        <!-- Main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>General Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">General Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-8">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Cadastramento de Unidades</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form id="unitregsitration" action="../../Controller/unitadd.php" method="post">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <label for="username">Nome da Unidade</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome da Unidade">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="email">Sigla</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="sigla" name="sigla" placeholder="Sigla">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="posto_grad">Tipo de Unidade</label>
                                                    <select class="form-control" id="tipo_unidade" name="tipo_unidade">
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="warname">Denominação Histórica</label>
                                                    <input type="text" class="form-control" id="denominacao_historica" name="denominacao_historica" placeholder="Nome de Guerra">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="codom">CODOM</label>
                                                    <input type="text" class="form-control" id="codom" name="codom" placeholder="Código de OM">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="form-group">
                                                    <label for="unidade">Subordinação</label>
                                                    <select class="form-control" id="subordinacao" name="subordinacao">
                                                        <? listUnitOnOption(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="password">Latitude</label>
                                                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="passwordcheck">Longitude</label>
                                                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Cadastrar Unidade</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-4">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Mapa</h3>
                                </div>
                                <div class="card-body">
                                    <div id="map" style="margin: 0; padding: 0; height: 350px;"> </div>
                                    <pre id="coordinates" class="coordinates"></pre>
                                </div>
                                <div class="card-footer">
                                    Mova o marcador para atualizar a posição da unidade.
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Usuários Cadastrados</h3>
                                </div>
                                <div class="card-body">
                                    <table id="usuarios1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Subordinação</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <? listUnitOnTable(1) ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Subordinação</th>
                                                <th>Ações</th>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../../dist/js/demo.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="../../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../../../plugins/jszip/jszip.min.js"></script>
    <script src="../../../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../../../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $("#usuarios1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "info": true,
            }).buttons().container().appendTo('#usuarios1_wrapper .col-md-6:eq(0)');
            $('#usuarios2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });


        mapboxgl.accessToken = 'pk.eyJ1IjoicmFmYWVsYWNpb2xpIiwiYSI6ImNraGM4b25wNjA2bngyd3MzaGdpbmIzYTkifQ.7At0FvYZ7dGUoyGAiaQHcg';
        var coordinates = document.getElementById('coordinates');

        var longitude = document.getElementById('longitude');
        var latitude = document.getElementById('latitude');

        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/rafaelacioli/cko1jpva00abt17nz2ml5ob5x',
            center: [-45.51704573077291, -23.040504824562788],
            zoom: 14
        });

        var marker = new mapboxgl.Marker({
                draggable: true
            })
            .setLngLat([-45.51, -23.04])
            .addTo(map);

        function onDragEnd() {
            var lngLat = marker.getLngLat();
            coordinates.style.display = 'block';
            coordinates.innerHTML =
                'Longitude: ' + lngLat.lng + '<br />Latitude: ' + lngLat.lat;

            latitude.value = lngLat.lat;
            longitude.value = lngLat.lng;
        }

        marker.on('dragend', onDragEnd);
    </script>
</body>

</html>