<?php
// Inicialize a sessão
session_start();

// Verifica se o usuário está logado, se não, redireciona-o para uma página de login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ./login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PO Virtual</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <link rel="icon" type="image/x-icon" href="./favicon.ico">

    <!-- Include the CesiumJS JavaScript and CSS files -->
    <!-- <script src="https://cesium.com/downloads/cesiumjs/releases/1.103/Build/Cesium/Cesium.js"></script> -->
    <!-- <link href="https://cesium.com/downloads/cesiumjs/releases/1.103/Build/Cesium/Widgets/widgets.css" rel="stylesheet"> -->

    <!-- Local links -->
    <script src="./js/node_modules/cesium/Build/Cesium/Cesium.js"></script>
    <link href="./js/node_modules/cesium/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

    <!-- Context Menu CSS  - https://github.com/UnrealSecurity/context-js -->
    <!-- <link rel="stylesheet" href="./js/context-menu/context/skins/chrome-dark.css" type="text/css" id="skin" /> -->

    <!-- Drawhelper -->
    <!-- <script src="./js/DrawHelper.js"></script>
    <link rel="stylesheet" type="text/css" href="./js/DrawHelper.css"> -->

    <?php 
        // Cria uma variável global para o JS armazenas as irformações do usuário;
        echo "<script> const USERNAME = '" . $_SESSION["username"] . "'; const USERID = " . $_SESSION["userid"] . ";</script>";
    ?>

    <style>
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
        <aside class="main-sidebar sidebar-dark-primary elevation-3" style="background-color: #060606;">

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

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="media/user.png" class="img-circle elevation-2" alt=<?php echo $_SESSION["username"]; ?> >
                    </div>
                    <div class="info">
                        <a href="#" onclick="closeSession()" class="d-block"> <?php echo $_SESSION["username"]; ?> </a>
                    </div>
                </div>


                <!-- SidebarSearch Form -->
                <!-- <div class="form-inline mt-3  mb-3 d-flex">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Pesquisar" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div> -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul id="menuLateral" class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
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
                                    <a href="#" class="nav-link ">
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
                                                <div>
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
                                                <div>
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
                                                <div>
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
                                                <div>
                                                    <input type="range" class="custom-range" id="bdgex_1to250000_range" min="0" max="1" step="0.01" hidden="true" value="1" onchange="rangeLayer(this, 4)">
                                                </div>
                                            </a>

                                        </li>
                                    </ul>
                                </li>

                                <!-- Rotas Especiais -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link ">
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
                                                <div>
                                                    <input type="range" class="custom-range" id="aerodromos_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 10)">
                                                </div>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="rotas1" onchange="showCPC()">
                                                    <label class="custom-control-label" for="rotas1">REH - KML (São Paulo)</label>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="rehSaoPaulo" onchange="showLayer(this,`rehSaoPaulo_range`, 6)">
                                                    <label class="custom-control-label" for="rehSaoPaulo">REH São Paulo</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="rehSaoPaulo_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 6)">
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="rehRioDeJaneiro" onchange="showLayer(this,`rehRioDeJaneiro_range`, 7)">
                                                    <label class="custom-control-label" for="rehRioDeJaneiro">REH Rio de Janeiro</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="rehRioDeJaneiro_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 7)">
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="rehCuritiba" onchange="showLayer(this,`rehCuritiba_range`, 8)">
                                                    <label class="custom-control-label" for="rehCuritiba">REH Curitiba</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="rehCuritiba_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 8)">
                                                </div>
                                            </a>
                                        </li> -->
                                        <!-- <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="atq3Pel" onchange="showLayer(this,`atq3Pel_range`, 13)">
                                                    <label class="custom-control-label" for="atq3Pel">Atq 3º Pel</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="atq3Pel_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 13)">
                                                </div>
                                            </a>
                                        </li> -->

                                        <!-- 
                                            <li class="nav-item">
                                                  <a class="nav-link ">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="manuLayer" onchange="showLayer(this,`manuLayer_range`, 5)">
                                                        <label class="custom-control-label" for="manuLayer">Manu</label>
                                                    </div>
                                                    <div>
                                                        <input type="range" class="custom-range" id="manuLayer_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 5)">
                                                    </div>
                                                </a>
                                            </li> 
                                        -->
                                    </ul>
                                </li>

                                <!-- Rotas Especiais -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link ">
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
                                                <div>
                                                    <input type="range" class="custom-range" id="osm_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 9)">
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Calcos de Operações -->
                                <li class="nav-item ">
                                    <a href="#" class="nav-link ">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>
                                            Entidades
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        <li class="nav-item">
                                            <a href="#" class="nav-link ">
                                                <i class="fas fa-thumbtack nav-icon"></i>
                                                <p>
                                                    Peças de Manobra
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>

                                            <ul class="nav nav-treeview" id="listaPecasManobra">

                                            </ul>

                                        </li>

                                        <li class="nav-item">
                                            <a href="#" class="nav-link ">
                                                <i class="fas fa-ruler-combined nav-icon"></i>
                                                <p>
                                                    Medidas de Coord.
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>

                                            <ul class="nav nav-treeview" id="listaMedidasCoordenacao">

                                            </ul>

                                        </li>

                                        <!-- <li class="nav-header">Taubaté</li>
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="atq1Pel" onchange="showLayer(this,`atq1Pel_range`, 11)">
                                                    <label class="custom-control-label" for="atq1Pel">Atq 1º Pel</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="atq1Pel_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 11)">
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="atq2Pel" onchange="showLayer(this,`atq2Pel_range`, 12)">
                                                    <label class="custom-control-label" for="atq2Pel">Atq 2º Pel</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="atq2Pel_range" min="0" max="1" step="0.1" hidden="true" value="1" onchange="rangeLayer(this, 12)">
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="incamv" onchange="showLayer(this,`incamv_range`, 13)">
                                                    <label class="custom-control-label" for="incamv">Incursão Aeromóvel</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="incamv_range" min="0" max="1" step="0.1" value="1" onchange="rangeLayer(this, 13)" hidden="true">
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-header">Lins</li> -->

                                        <!-- <li class="nav-item">
                                            <a class="nav-link ">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="recvig" onchange="showLayer(this,`recvig_range`, 14)">
                                                    <label class="custom-control-label" for="recvig">Operação Final</label>
                                                </div>
                                                <div>
                                                    <input type="range" class="custom-range" id="recvig_range" min="0" max="1" step="0.1" value="1" onchange="rangeLayer(this, 14)" hidden="true">
                                                </div>
                                            </a>
                                        </li> -->

                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- Desenhos -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-drafting-compass nav-icon"></i>
                                <p>
                                    Desenho
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview ">
                                <li class="nav-item my-1 ">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" id="btnInsertGeoImage" onclick="$('#insertGeoImageModal').modal();">
                                            <i class="fas fa-map-marker-alt"></i>
                                            GeoImagem
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item my-1 ">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" id="btnInsertPoint" onclick="insertPoint();">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Peça de Manobra
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item my-1 ">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" id="btnInsertPoint" onclick="insertLabel();">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Texto
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="moveEntity();">
                                            <i class="fas fa-arrows-alt"></i>
                                            Mover Peça
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="drawFree();">
                                            <i class="fas fa-pencil-alt"></i>
                                            Desenhar livremente
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="dialogPolyline();">
                                            <i class="fas fa-draw-polygon"></i>
                                            Linha
                                        </button>
                                    </a>
                                </li>
                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="dialogDashedPolyline();">
                                            <i class="fas fa-draw-polygon"></i>
                                            Linha Tracejada
                                        </button>
                                    </a>
                                </li>

                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="dialogPolygon2D();">
                                            <i class="fas fa-draw-polygon"></i>
                                            Polígono 2D
                                        </button>
                                    </a>
                                </li>

                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="dialogPolygon();">
                                            <i class="fas fa-draw-polygon"></i>
                                            Polígono 3D
                                        </button>
                                    </a>
                                </li>

                                <li class="nav-item my-1">
                                    <a class="nav-item">
                                        <button type="button" class="btn btn-light btn-block btn-flat" onclick="measurementPoints();">
                                            <i class="fas fa-ruler"></i>
                                            Medir Distâncias
                                        </button>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Editor CZML -->
                        <li class="nav-item">
                            <a href="https://www.visionport.com/cesium-kml-czml-editor/" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-globe"></i>
                                <p>
                                    Editor CZML
                                    <!-- <span class="right badge badge-info">Novo</span> -->
                                </p>
                            </a>
                        </li>

                        <!-- Pós Ação -->
                        <li class="nav-item">
                            <a href="./apa.php" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-clipboard"></i>
                                <p>
                                    Avaliação Pós Ação
                                </p>
                            </a>
                        </li>

                        <!-- Carregar CZML -->
                        <li class="nav-item">
                            <a class="nav-link">
                                <i class="nav-icon fas fa-upload"></i>
                                <p>
                                    Carregar CZML
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li>
                                    <a class="nav-item">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="fileUpload" accept=".czml">
                                                <label class="custom-file-label" for="fileUpload">Arquivo</label>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                         <!-- Carregar KML/KMZ -->
                         <li class="nav-item">
                            <a class="nav-link">
                                <i class="nav-icon fas fa-upload"></i>
                                <p>
                                    Carregar KML/KMZ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li>
                                    <a class="nav-item">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="kmlFileUpload" accept=".kml,.kmz">
                                                <label class="custom-file-label" for="kmlFileUpload">Arquivo</label>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">OPÇÕES</li>

                        <li class="nav-item">
                            <a class="nav-link">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="paths" onchange="showPaths()">
                                    <label class="custom-control-label" for="paths" alt="Exibe a rota desenvolvida pelas peças de manobra">Caminhos</label>
                                </div>
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
            <div id="toolbar" class="toolbar-left">

            </div>
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
                    <button type="button" class="btn btn-primary" id="btnCreateEntity" onclick="createEntity();">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Billboard -->

    <!-- Modal Label -->
    <div class="modal fade" id="insertLabelModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Inserir Texto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="createLabelTexto">Texto</label>
                                    <input type="text" class="form-control" id="createLabelTexto" placeholder="Nome da peça de manobra">
                                </div>
                            </div>
                            
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="createLabelTamanho">Tamanho</label>
                                    <input type="number" class="form-control" id="createLabelTamanho" min="12" max="40" value="13" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="createLabelLatitude">Latitude</label>
                                    <input type="text" class="form-control" id="createLabelLatitude" placeholder="-23.5874988">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="createLabelLongitude">Longitude</label>
                                    <input type="text" class="form-control" id="createLabelLongitude" placeholder="-45.6987455">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="createLabelAltitude">Altitude</label>
                                    <input type="text" class="form-control" id="createLabelAltitude" placeholder="612">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnCreateLabel" onclick="createLabel();">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Label -->

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


    <!-- Modal Image  -->
    <div class="modal fade" id="insertGeoImageModal" tabindex="-1" role="dialog" aria-labelledby="GeoImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="GeoImageModalLabel">Inserir Imagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> <i class="fas fa-info"></i> Para inserir uma imagem corretamente, você precisa informar as máximas e mínimas latitudes e longitude nos campos correspondentes.</p>
                    <form>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col align-self-end" style="text-align: -webkit-right">
                                <div class="form-group">
                                    <label for="createGeoImageMaxLatitude">Máx Latitude</label>
                                    <input type="text" class="form-control" id="createGeoImageMaxLatitude" placeholder="-23.5874988" style="width: 11em">
                                </div>
                            </div>
                            <div class="col align-self-end">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col align-self-end text-right">
                                <div class="form-group" style="text-align: -webkit-right">
                                    <label for="createGeoImageMinLongitude">Min Longitude</label>
                                    <input type="text" class="form-control" id="createGeoImageMinLongitude" placeholder="-45.6987455" style="width: 11em">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <center><img class="rounded mx-auto d-block shadow img-thumbnail" style="max-height: 450px;" id="outputGeoImage"></center>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="createGeoImageMaxLongitude">Máx Longitude</label>
                                    <input type="text" class="form-control" id="createGeoImageMaxLongitude" placeholder="-45.6987455" style="width: 11em">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col align-self-start text-left">
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="createGeoImageMinLatitude">Min Latitude</label>
                                    <input type="text" class="form-control" id="createGeoImageMinLatitude" placeholder="-23.5874988" style="width: 11em">
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input id="createGeoImage" type="file" class="custom-file-input" accept="image/png" onchange="loadGeoImage(event);">
                                        <label id="createGeoImageLabel" class="custom-file-label" for="createGeoImage">Escolha a imagem (.png)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btncreateGeoImage" onclick="insertGeoImage();">Carregar no mapa</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Image -->

    <!-- GeoLocation required modal -->

    <div class="modal fade" id="geoLocationModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="geoLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="geoLocationModalLabel">Ative a localização do seu navegador</h5>
                </div>
                <div class="modal-body">
                    Para utilizar a aplicação, você deve habilitar a localização do seu navegador.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="location.reload();">Recarregar página</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End GeoLocation required modal -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>

    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Cesium Navigation -->
    <script>
        Cesium.defineProperties = Object.defineProperties;
    </script>
    <script src="./js/viewerCesiumNavigationMixin.min.js"></script>

    <!-- Cesium JS configuration file -->
    <script src="js/pov.js"></script>

    <!-- Websocket configuration file -->
    <!-- <script src="js/websocket.js"></script> -->

    <!-- Controls -->
    <!-- <script src="js/controls.js"></script> -->

    <!-- Context Menu -->
    <!-- <script src="js/context-menu/context/context.js"></script> -->

</body>

</html>