<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizador</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <!-- Include the CesiumJS JavaScript and CSS files -->
    <!-- <script src="https://cesium.com/downloads/cesiumjs/releases/1.103/Build/Cesium/Cesium.js"></script> -->
    <!-- <link href="https://cesium.com/downloads/cesiumjs/releases/1.103/Build/Cesium/Widgets/widgets.css" rel="stylesheet"> -->

    <!-- Local links -->
    <script src="./js/node_modules/cesium/Build/Cesium/Cesium.js"></script>
    <link href="./js/node_modules/cesium/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

    <!-- Context Menu CSS  - https://github.com/UnrealSecurity/context-js -->
    <link rel="stylesheet" href="./js/context-menu/context/skins/chrome-dark.css" type="text/css" id="skin" />

    <!-- Drawhelper -->
    <!-- <script src="./js/DrawHelper.js"></script>
    <link rel="stylesheet" type="text/css" href="./js/DrawHelper.css"> -->

    <style>
        .contextMenu {
            background-color: white;
            border-radius: 2px;
            color: #ddd;
        }

        .contextMenuItem {
            padding: 5px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            justify-items: center;
            border-radius: 2px;
        }

        .contextMenuItem {
            /* margin-top: 5px; */
            border-radius: 2px;
        }

        .contextMenuItem span {
            margin-left: 5px;
        }

        .contextMenuItem:hover {
            background-color: lightblue;
            color: #000;
        }

        .panel {
            margin: 70px;
        }

        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .no {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .container {
            text-align: center;
        }

        .watermark {
            color: #787878;
            font-size: 80pt;
        }

        #cesiumContainer {
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .toolbar-left {
            display: block;
            position: absolute;
            top: 5px;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-3" style="background-color: #000;">

            <!-- Brand Logo -->
            <div class="brand-link d-flex justify-content-between align-items-center">
                <a class="brand-link">
                    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-1" style="max-height: 40px;">
                    <span class="brand-text font-weight-light">Plan Op Vrt</span>
                </a>
                <a class="pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>
                                    Camadas
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <!-- Cartas Topográficas BDGEx -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link bg-gray-dark">
                                        <i class="far fa-map nav-icon"></i>
                                        <p>
                                            BDGEx
                                            <i class=" right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="bdgex_1to25000" onchange="showLayer(this,`bdgex_1to25000_range`, 1)">
                                                    <label class="custom-control-label" for="bdgex_1to25000">BDGEx -
                                                        1:25.000</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="bdgex_1to25000_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 1)">
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link">
                                                <div class="custom-control custom-switch">

                                                    <input type="checkbox" class="custom-control-input" id="bdgex_1to50000" onchange="showLayer(this,`bdgex_1to50000_range`, 2)">
                                                    <label class="custom-control-label" for="bdgex_1to50000">BDGEx -
                                                        1:50.000</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="bdgex_1to50000_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 2)">
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link">
                                                <div class="custom-control custom-switch">

                                                    <input type="checkbox" class="custom-control-input" id="bdgex_1to100000" onchange="showLayer(this,`bdgex_1to100000_range`, 3)">
                                                    <label class="custom-control-label" for="bdgex_1to100000">BDGEx -
                                                        1:100.000</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="bdgex_1to100000_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 3)">
                                                </div>
                                            </a>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link">
                                                <div class="custom-control custom-switch">

                                                    <input type="checkbox" class="custom-control-input" id="bdgex_1to250000" onchange="showLayer(this,`bdgex_1to250000_range`, 4)">
                                                    <label class="custom-control-label" for="bdgex_1to250000">BDGEx -
                                                        1:250.000</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="bdgex_1to250000_range" min="0" max="1" step="0.01" hidden="true" value="1" onchange="rangeLayer(this, 4)">
                                                </div>
                                            </a>

                                        </li>
                                    </ul>
                                </li>

                                <!-- Rotas Especiais -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link bg-gray-dark">
                                        <i class="fas fa-satellite nav-icon"></i>
                                        <p>
                                            Info Aeronáuticas
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="aerodromos" onchange="showLayer(this,`aerodromos_range`, 10)">
                                                    <label class="custom-control-label" for="aerodromos">Aeródromos</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="aerodromos_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 10)">
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Rotas Especiais -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link bg-gray-dark">
                                        <i class="fas fa-server nav-icon"></i>
                                        <p>
                                            Provedores de Imagem
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="osm" onchange="showLayer(this,`osm_range`, 9)">
                                                    <label class="custom-control-label" for="osm">Open Street Map</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="osm_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 9)">
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Calcos de Operações -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link bg-gray-dark">
                                        <i class="fas fa-server nav-icon"></i>
                                        <p>
                                            CPC 2023
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                    <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="recvig" onchange="showLayer(this,`recvig_range`, 14)">
                                                    <label class="custom-control-label" for="recvig">Rec/Vigilância</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="recvig_range" min="0" max="1" step="0.1" value="1" onchange="rangeLayer(this, 14)" hidden="true">
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="atqamv" onchange="showLayer(this,`atqamv_range`, 15)">
                                                    <label class="custom-control-label" for="atqamv">Ataque Amv</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="atqamv_range" min="0" max="1" step="0.1" value="1" onchange="rangeLayer(this, 15)" hidden="true">
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="incamv" onchange="showLayer(this,`incamv_range`, 16)">
                                                    <label class="custom-control-label" for="incamv">Incursão Amv</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="range" class="custom-range" id="incamv_range" min="0" max="1" step="0.1" value="1" onchange="rangeLayer(this, 16)" hidden="true">
                                                </div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="paths" onchange="showPaths()">
                                            <label class="custom-control-label" for="paths" alt="Exibe a rota desenvolvida pelas peças de manobra">Caminhos</label>
                                        </div>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        
                        <!-- Editor CZML -->
                        <li class="nav-item">
                            <a href="./apa_deslocamento.php" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    APA Deslocamento Ter
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="./apa_recvig.php" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    APA Rec/Vigilância
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="./apa_atqamv.php" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    APA Ataque Amv
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="./apa_incamv.php" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    APA Incursão Amv
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>



        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div id="cesiumContainer"></div>
            <div id="toolbar" class="toolbar-left"></div>
            <div id="contextMenu"></div>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- The Modal -->
        <div id="myModal" class="modal modal-dialog modal-x1">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Some text in the Modal..</p>
            </div>
        </div>


    </div>
    <!-- ./wrapper -->

    <!-- Modal Billboard -->
    <div class="modal fade" id="insertPointModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Peça de Manobra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <img class="rounded mx-auto d-block shadow img-thumbnail" id="outputImage">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input id="createEntityImage" type="file" class="custom-file-input" accept="image/png" onchange="loadImage(event);">
                                        <label id="createEntityImageLabel" class="custom-file-label" for="createEntityImage">Escolha a imagem/calunga</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="createEntityNome">Nome</label>
                                    <input type="text" class="form-control" id="createEntityNome" placeholder="Nome da peça de manobra">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="createEntityMatricula">Matrícula/Indicativo</label>
                                    <input type="text" class="form-control" id="createEntityMatricula" placeholder="EB1001 / VBTP-5547 / FORÇA15">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="createEntityLatitude">Latitude</label>
                                    <input type="text" class="form-control" id="createEntityLatitude" placeholder="-23.5874988">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="createEntityLongitude">Longitude</label>
                                    <input type="text" class="form-control" id="createEntityLongitude" placeholder="-45.6987455">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="createEntityAltitude">Altitude</label>
                                    <input type="text" class="form-control" id="createEntityAltitude" placeholder="612">
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnCreateEntity" onclick="createEntity(event);">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Billboard -->

    <!-- Modal Cilinder  -->
    <div class="modal fade" id="insertCylinder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Cilindro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <img class="rounded mx-auto d-block shadow img-thumbnail" id="outputImage">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input id="createEntityImage" type="file" class="custom-file-input" accept="image/png" onchange="loadImage(event);">
                                        <label id="createEntityImageLabel" class="custom-file-label" for="createEntityImage">Escolha a imagem/calunga</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="createEntityNome">Nome</label>
                                    <input type="text" class="form-control" id="createEntityNome" placeholder="Nome da peça de manobra">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="createEntityMatricula">Matrícula/Indicativo</label>
                                    <input type="text" class="form-control" id="createEntityMatricula" placeholder="EB1001 / VBTP-5547 / FORÇA15">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="createEntityLatitude">Latitude</label>
                                    <input type="text" class="form-control" id="createEntityLatitude" placeholder="-23.5874988">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="createEntityLongitude">Longitude</label>
                                    <input type="text" class="form-control" id="createEntityLongitude" placeholder="-45.6987455">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="createEntityAltitude">Altitude</label>
                                    <input type="text" class="form-control" id="createEntityAltitude" placeholder="612">
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnCreateEntity" onclick="createEntity(event);">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Cilinder  -->

    <!-- Modal Polygon -->
    <div class="modal fade" id="insertPolygonModal" tabindex="-1" role="dialog" aria-labelledby="polygonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="polygonModalLabel">Inserir Polígono</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="insertPolygonNome">Nome</label>
                                    <input type="text" class="form-control" id="insertPolygonNome">
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="insertPolygonRaio">Raio (Km)</label>
                                    <input type="number" class="form-control" id="insertPolygonRaio" min="1" value="1" required>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="insertPolygonVertices">Vértices</label>
                                    <input type="number" class="form-control" id="insertPolygonVertices" min="3" max="36" placeholder="Mínimo 3 e máximo de 36" value="3" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="insertPolygonRaio">Raio (m)</label>
                                    <input type="number" class="form-control" id="insertPolygonRaio" min="1" value="500" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="insertPolygonColor">Cor </label>
                                    <input type="color" id="insertPolygonColor" list="presetColors" value="#ffff00">
                                    <datalist id="presetColors">
                                        <option>#ff0000</option>
                                        <option>#ffff00</option>
                                        <option>#00ff00</option>
                                        <option>#0000ff</option>
                                        <option>#ffffff</option>
                                        <option>#ff00ff</option>
                                        <option>#00ffff</option>
                                        <option>#ff9933</option>
                                        <option>#cccc00</option>
                                        <option>#8f8f8f</option>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <h5>Posição central no mapa</h5>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="insertPolygonLatitude">Latitude</label>
                                    <input type="text" class="form-control" id="insertPolygonLatitude">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="insertPolygonLongitude">Longitude</label>
                                    <input type="text" class="form-control" id="insertPolygonLongitude">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="insertPolygonAzimute">Azimute</label>
                                    <input type="text" class="form-control" id="insertPolygonAzimute" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="insertPolygonAltura">Altura do polígono (m) </label>
                                    <input type="number" class="form-control" id="insertPolygonAltura" value="1">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="insertPolygonAltitude">Altitude do solo (m) </label>
                                    <input type="number" class="form-control" id="insertPolygonAltitude" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="insertPolygonFixarSolo"><br>
                                    <label class="custom-control-label" for="insertPolygonFixarSolo">Fixar no solo</label>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnCreateEntity" onclick="savePolygon(event);">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Polygon -->

    <!-- REQUIRED SCRIPTS -->

    <!-- https://github.com/3DGISKing/CesiumJsSamples/tree/master/Mixins/NavigationMixin -->
    <script>
        Cesium.defineProperties = Object.defineProperties;
    </script>

    <script src="./js/viewerCesiumNavigationMixin.min.js"></script>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <!-- Cesium JS configuration file -->
    <script src="js/visualizador.js"></script>

    <!-- Websocket configuration file -->
    <!-- <script src="js/websocket.js"></script> -->

    <!-- Controls -->
    <!-- <script src="js/controls.js"></script> -->

    <!-- Context Menu -->
    <!-- <script src="js/context-menu/context/context.js"></script> -->

    <!-- Cesium Navigation -->

    <!-- <script src="./js/cesium-navigation/Source/viewerCesiumNavigationMixin.js"></script> -->

</body>

</html>