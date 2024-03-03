// Cesium SEAC App Javascript File
// Created at: 16/03/2023, by Rafael Acioli
Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlNjc1MmFiNy00NTEyLTRlYzgtOWRhMi02M2U0ZmMzMmE2MDciLCJpZCI6MTA3NDYyLCJpYXQiOjE2NjQ2MzYxNzh9.tFjf2tV8S9AzEOVdTSL6kqXTkT_8mly-YsLHwMUaYGY';

const extent = Cesium.Rectangle.fromDegrees(-97.53404833588318, -33.72606178475663, -28.69683591004248, 15.079230916500306);

Cesium.Camera.DEFAULT_VIEW_RECTANGLE = extent;
Cesium.Camera.DEFAULT_VIEW_FACTOR = 0.5;

// Initialize the Cesium Viewer in the HTML element with the `cesiumContainer` ID.
const viewer = new Cesium.Viewer('cesiumContainer', {
    terrainProvider: Cesium.createWorldTerrain({
        requestWaterMask: true,
    }),
    timeline: false,
    animation: false,
    instructions: false,
    navigationHelpButton: false,
    infoBox: true,
    fullscreenButton: true,
    homeButton: true,
    baseLayerPicker: false,

    // Descomente/Comente a linha abaixo para reduzir/aumentar a qualidade da imagem de satélite.
    imageryProvider: new Cesium.IonImageryProvider({ assetId: 3954 }), // Ativado: reduz a qualidade.

    // Habilita a pesquisa de locais
    geocoder: true,
});

// Allow run scripts on infobox-cesium
// https://community.cesium.com/t/cant-run-scripts-in-infobox/11956

viewer.infoBox.frame.removeAttribute("sandbox");
viewer.infoBox.frame.src = "about:blank";


// Enable ligth of sun
viewer.scene.globe.enableLighting = true;

// Add Cesium OSM Buildings, a global 3D buildings layer.
// const buildingTileset = viewer.scene.primitives.add(Cesium.createOsmBuildings());

let scene = viewer.scene;
let layers = viewer.scene.imageryLayers;
let handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);
let floatingPoint;
let activeShapePoints = [];

// Verify if there is a entity/mcc to remove from viewer
let timeToCheckEntities = false;
let timeToCheckMCC = false;

// Enable the Depth Test when insert a entity on terrain
viewer.scene.globe.depthTestAgainstTerrain = true;

viewer.clock.startTime = Cesium.JulianDate.now();
viewer.clock.currentTime = Cesium.JulianDate.now();
viewer.clock.shouldAnimate = true;
// let lastUpdated = viewer.clock.currentTime;




// -- Layers -- 
// Add BDGEx 1:25.000 to index 2
const bdgex1 = new Cesium.WebMapServiceImageryProvider({
    // url: new Cesium.Resource({
    //     url: "https://bdgex.eb.mil.br/mapcache",
    //     proxy: new Cesium.DefaultProxy("/proxy/"),
    // }),
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm25",
    enablePickFeatures: false,
});

// Add BDGEx 1:50.000 to index 3
const bdgex2 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm50",
    enablePickFeatures: false,
});

// Add BDGEx 1:100.000 to index 4
const bdgex3 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm100",
    enablePickFeatures: false,
});

// Add BDGEx 1:250.000 to index 5
const bdgex4 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm250",
    enablePickFeatures: false,
});

// Add a layer for REH SP
const rotas1 = Cesium.KmlDataSource.load('./media/reh-sp.kml', {
    camera: viewer.scene.camera,
    canvas: viewer.scene.canvas,
    clampToGround: true,
});

const rehSaoPaulo = new Cesium.WebMapServiceImageryProvider({
    url: "https://geoaisweb.decea.mil.br/geoserver/ICA/wms",
    layers: "REH_SAO_PAULO_2",
    enablePickFeatures: false,
    parameters: {
        transparent: true,
        format: "image/png",
    },
});

const rehRioDeJaneiro2_3 = new Cesium.WebMapServiceImageryProvider({
    url: "https://geoaisweb.decea.mil.br/geoserver/ICA/wms",
    layers: "REH_RIO_DE_JANEIRO_2_E_3",
    enablePickFeatures: false,
    parameters: {
        transparent: true,
        format: "image/png",
    },
});

const rehCuritiba = new Cesium.WebMapServiceImageryProvider({
    url: "https://geoaisweb.decea.mil.br/geoserver/ICA/wms",
    layers: "REH_CURITIBA",
    enablePickFeatures: false,
    parameters: {
        transparent: true,
        format: "image/png",
    },
});

//  Exemplo de utilização de uma imagem no terreno. Para isso é necessário informar 
//  as latitudes/longitudes máxima e mínima
const manu = new Cesium.SingleTileImageryProvider({
    url: "./media/manu.jpg",
    rectangle: Cesium.Rectangle.fromDegrees(
        -45.5328204789989,
        -23.042778484253187,
        -45.51023265207758,
        -23.025935987511325,
    ),
});

const atq1Pel = new Cesium.SingleTileImageryProvider({
    url: "./media/cpc2023/Atq1Pel.png",
    rectangle: Cesium.Rectangle.fromDegrees(
        -45.75008,
        -23.49878,
        -44.99929,
        -22.75051,
    ),
    hasAlphaChannel: true,
});

const atq2Pel = new Cesium.SingleTileImageryProvider({
    url: "./media/cpc2023/atq2pel.png",
    rectangle: Cesium.Rectangle.fromDegrees(
        -45.75008,
        -23.49878,
        -44.99929,
        -22.75051,
    ),
});

const incamv = new Cesium.SingleTileImageryProvider({
    url: "./media/cpc2023/atq2pel.png",
    rectangle: Cesium.Rectangle.fromDegrees(
        -45.75008,
        -23.49885,
        -44.99919,
        -22.75071,
    ),
});

const recvig_lins = new Cesium.SingleTileImageryProvider({
    url: "./media/cpc2023/recvig_lins.png",
    rectangle: Cesium.Rectangle.fromDegrees(
        -50.00000,
        -22.50000,
        -49.50000,
        -21.50000,
    ),
});

const osm = new Cesium.OpenStreetMapImageryProvider({
    url: 'https://a.tile.openstreetmap.org/',
});

const aerodromos = new Cesium.WebMapServiceImageryProvider({
    url: "https://geoaisweb.decea.mil.br/geoserver/ICA/wms",
    layers: "airport",
    enablePickFeatures: true,
    parameters: {
        transparent: true,
        format: "image/png",
    },
});

// // Add a layer for CPC2023
// const cpc2023 = Cesium.KmlDataSource.load('./media/cpc2023/Atq2Pel/doc.kml', {
//     camera: viewer.scene.camera,
//     canvas: viewer.scene.canvas,
//     clampToGround: true,
// });

//  BDGEx Layer
layers.addImageryProvider(bdgex1, 1);
layers.addImageryProvider(bdgex2, 2);
layers.addImageryProvider(bdgex3, 3);
layers.addImageryProvider(bdgex4, 4);

// Image Layer
layers.addImageryProvider(manu, 5);

//  REH Layers
layers.addImageryProvider(rehSaoPaulo, 6);
layers.addImageryProvider(rehRioDeJaneiro2_3, 7);
layers.addImageryProvider(rehCuritiba, 8);

// OSM Layer
layers.addImageryProvider(osm, 9);

// GEOAISWEB
layers.addImageryProvider(aerodromos, 10);

// CPC 2023
layers.addImageryProvider(atq1Pel, 11);
layers.addImageryProvider(atq2Pel, 12);
layers.addImageryProvider(incamv, 13);
layers.addImageryProvider(recvig_lins, 14);


// Set 'show' to layers add before
layers.get(1).show = false;
layers.get(2).show = false;
layers.get(3).show = false;
layers.get(4).show = false;
layers.get(5).show = false;
layers.get(6).show = false;
layers.get(7).show = false;
layers.get(8).show = false;
layers.get(9).show = false;
layers.get(10).show = false;
layers.get(11).show = false;
layers.get(12).show = false;
layers.get(13).show = false;
layers.get(14).show = false;

// -- End Layers -- 


// Data Sources que controlam as peças de manobra e as medidada de coordenação presentes na visualização

let cardsDataSourceFromServer = new Cesium.CustomDataSource({
    name: "cards",
})

let cardsDataSourceOnCesium = new Cesium.CustomDataSource({
    name: "cards",
})

let coordinationMeasuresDataSourceOnServer = new Cesium.CustomDataSource({
    name: "coordinationMeasuresFromServer",
})

let coordinationMeasuresDataSourceOnCesium = new Cesium.CustomDataSource({
    name: "coordinationMeasuresOnCesium",
})

viewer.dataSources.add(coordinationMeasuresDataSourceOnServer);

// -- Websocket Client configuration
function connectSocket() {
    console.log("Attempt connection");
    // const socket = new WebSocket("ws://186.250.70.138:3000")
    const socket = new WebSocket("ws://192.168.0.111:3000")
    // const socket = new WebSocket("ws://10.12.205.40:3000");

    let coordinationMeasures;
    let entities;

    socket.onopen = function () {
        console.log("We are connected");
       
        let message = {
            code: "getEntities",
            message: "",
        };
    
        socket.send(JSON.stringify(message));
    }

    socket.onmessage = function (e) {
        let data = JSON.parse(e.data);

        console.log(data);

        coordinationMeasures = data[0];
        updateCoordinationMeasures(coordinationMeasures);

        entities = data[1];
        updateEntities(entities);
    };

    socket.onclose = function (e) {
        console.log('Socket is closed. Reconnect will be attempted.', e.reason);
    }

    
}
// -- End Websocket Client configuration
function updateCoordinationMeasures(coordinationMeasures) {

    let coordinationMeasuresOnServer = coordinationMeasures;
    let coordinationMeasuresOnCesium = coordinationMeasuresDataSourceOnServer.entities.values;

    for (let i = 0; i < coordinationMeasuresOnServer.length; i++) {
        const coordinationMeasureOnServer = coordinationMeasuresOnServer[i];

        let check = false;

        for (let j = 0; j < coordinationMeasuresOnCesium.length; j++) {
            let coordinationMeasureOnCesium = coordinationMeasuresOnCesium[j];

            if (coordinationMeasureOnServer.id == coordinationMeasureOnCesium.id) {
                check = true;
            }
        }

        if (check == false) {
            addCoordinationMeasureToCesium(coordinationMeasureOnServer);
        }
    }
}

function addCoordinationMeasureToCesium(coordinationMeasure) {
    let measurement;

    let deleteMeasure = function (id) {
        let data = { id: id };
        let ajax = new XMLHttpRequest();

        ajax.open("POST", "./source/controller/deleteMCC.php", true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
                let respostaAjax = ajax.responseText;

                if (respostaAjax == "ok") {
                    coordinationMeasuresDataSourceOnServer.entities.removeById(id);
                }
            }
        }
        ajax.send(JSON.stringify(data));
    }

    let createButton = function (coordinationMeasure) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'cesium-button';
        button.onclick = function () {
            deleteMeasure(coordinationMeasure.id)
            button.remove();
        };
        button.textContent = "Deletar Medida de Coordenação";
        document.getElementById('toolbar').appendChild(button);

    }
    // console.log(`ID: ${coordinationMeasure.id} COORD: ${coordinationMeasure.coordenadas}`);

    switch (coordinationMeasure.tipo_medida_coordenacao) {
        case 1:
            measurement = coordinationMeasuresDataSourceOnServer.entities.add({
                id: coordinationMeasure.id,
                name: coordinationMeasure.name,
                polyline: {
                    positions: Cesium.Cartesian3.fromDegreesArray(
                        coordinationMeasure.coordenadas.split(",").map(Number)
                    ),
                    clampToGround: true,
                    width: 6,
                    material: Cesium.Color.BLUE,
                }
            });
            measurement.description = `
                <h2>Linha ${coordinationMeasure.id} </h2>
                <br><b>Criado em: </b> ${coordinationMeasure.created_at}
            `;

            measurement.addProperty("isMCC");
            measurement.isMCC = true;

            break;
        case 2:
            let altura;
            let altitude;

            // Verificação necessária para a correta fixação do polígono no terreno caso a 
            // opção FIXAR NO SOLO esteja ativada
            if (coordinationMeasure.fixarSolo == 1) {
                altura = undefined;
                altitude = undefined;
            } else {
                altura = parseFloat(coordinationMeasure.altura);
                altitude = parseFloat(coordinationMeasure.altitude);
            }

            measurement = coordinationMeasuresDataSourceOnServer.entities.add({
                id: coordinationMeasure.id,
                position: Cesium.Cartesian3.fromDegrees(coordinationMeasure.longitude, coordinationMeasure.latitude),
                orientation: Cesium.HeadingPitchRoll(
                    Cesium.Math.toRadians(coordinationMeasure.azimute), 0, 0),
                polygon: {
                    hierarchy: Cesium.Cartesian3.fromDegreesArray(
                        coordinationMeasure.coordenadas.split(",").map(Number)
                    ),
                    material: Cesium.Color.fromCssColorString(coordinationMeasure.cor).withAlpha(0.5),
                    height: altura + altitude,
                    heightReference: Cesium.HeightReference.RELATIVE_TO_GROUND,
                    extrudedHeight: altitude,
                    extrudedHeightReference: Cesium.HeightReference.RELATIVE_TO_GROUND,
                    closeTop: true,
                    closeBottom: true,
                },
                label: {
                    text: coordinationMeasure.nome,
                    font: "14pt sans-serif",
                    heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
                    horizontalOrigin: Cesium.HorizontalOrigin.CENTER,
                    verticalOrigin: Cesium.VerticalOrigin.BASELINE,
                    fillColor: Cesium.Color.BLACK,
                    showBackground: true,
                    backgroundColor: new Cesium.Color(1, 1, 1, 0.7),
                    backgroundPadding: new Cesium.Cartesian2(8, 4),
                    disableDepthTestDistance: Number.POSITIVE_INFINITY, // draws the label in front of terrain
                    scaleByDistance: new Cesium.NearFarScalar(1.5e2, 0.8, 1.5e7, 0.3),
                    pixelOffsetScaleByDistance: new Cesium.NearFarScalar(
                        1.0e3,
                        1.0,
                        1.5e6,
                        0.0
                    ),
                    translucencyByDistance: new Cesium.NearFarScalar(
                        1.0e3,
                        1.0,
                        1.5e6,
                        0.1
                    ),
                },
            });

            measurement.addProperty("isMCC");
            measurement.isMCC = true;

            measurement.description = `
                <h2>Polígono ${coordinationMeasure.id} ${coordinationMeasure.nome}</h3>
                <br><b>Latitude central: </b> ${coordinationMeasure.latitude}
                <br><b>Longitude central: </b> ${coordinationMeasure.longitude}
                <br><b>Criado em: </b> ${coordinationMeasure.created_at}
            `;
            break;
        case 3:
            measurement = coordinationMeasuresDataSourceOnServer.entities.add({
                id: coordinationMeasure.id,
                polyline: {
                    positions: Cesium.Cartesian3.fromDegreesArray(coordinationMeasure.coordenadas.split(",").map(Number)),
                    material: Cesium.Color.fromRandom({
                        alpha: 1.0
                    }),
                    width: 4,
                    clampToGround: true,
                },
            });

            measurement.description = `
                <h2>Linha livre ${coordinationMeasure.id} </h2>
                <br><b>Criado em: </b> ${coordinationMeasure.created_at}
            `;

            measurement.addProperty("isMCC");
            measurement.isMCC = true;

            break;
        default:
            break;
    }
}

function removeCoordinationMeasureToCesium(coordinationMeasures) {
    if (timeToCheckMCC) {

        let coordinationMeasuresOnServer = coordinationMeasures;
        let coordinationMeasuresOnCesium = coordinationMeasuresDataSourceOnServer.entities.values;

        console.log("Verifing if mcc are updated...")

        for (let i = 0; i < coordinationMeasuresOnCesium.length; i++) {
            const coordinationMeasureOnCesium = coordinationMeasuresOnCesium[i];

            let check = false;

            for (let j = 0; j < coordinationMeasuresOnServer.length; j++) {
                const coordinationMeasureOnServer = coordinationMeasuresOnServer[j];

                if (coordinationMeasureOnCesium.id == coordinationMeasureOnServer.id) {
                    check = true;
                    break;
                }
            }
            if (check == false) {
                coordinationMeasuresDataSourceOnServer.entities.removeById(coordinationMeasureOnCesium.id);
            }
        }
    }
    timeToCheckMCC = false
}

//  Controll the entities
async function updateEntities(entities) {

    let entitiesOnServer = entities;

    let entitiesOnCesium = viewer.entities.values;

    for (let i = 0; i < entitiesOnServer.length; i++) {
        const entityOnServer = entitiesOnServer[i];

        let check = false;

        for (let j = 0; j < entitiesOnCesium.length; j++) {
            let entityOnCesium = entitiesOnCesium[j];

            if (entityOnServer.id == entityOnCesium.id) {
                check = true;

                

                // Update entity position and description
                entityOnCesium.description = `
                    <h2>${entityOnServer.nome}</h2>
                    <h3>${entityOnServer.descricao}</h3>
                    <b>Latitude</b>: ${entityOnServer.latitude}
                    <br><b>Longitude</b>: ${entityOnServer.longitude}
                    <br><b>Altitude</b>: ${entityOnServer.altitude}
                    <br><b>Velocidade</b>: ${entityOnServer.azimute}
                    <br><b>Data/Hora</b>: ${entityOnServer.updated_at}
                `;

                if (entityOnServer.longitude != undefined && entityOnServer.latitude != undefined && entityOnServer.altitude != undefined) {
                    if (Cesium.Cartesian3.fromDegrees(entityOnCesium.lastPosition) != entityOnCesium.position) {


                        let position = Cesium.Cartesian3.fromDegrees(entityOnServer.longitude, entityOnServer.latitude, entityOnServer.altitude);
                        let positionsSamples = entityOnCesium.position;
                        positionsSamples.addSample(Cesium.JulianDate.now(), position);

                        entityOnCesium.pathPositions += `, ${entityOnServer.longitude}, ${entityOnServer.latitude}, ${entityOnServer.altitude}`;
                        entityOnCesium.lastPosition = `${entityOnServer.longitude}, ${entityOnServer.latitude}, ${entityOnServer.altitude}`;
                    }
                }
                if (entityOnServer.mostrar == 0) {
                    entityOnCesium.show = false;
                } else {
                    entityOnCesium.show = true;
                }
            }
        }
        if (check == false) {
            addEntity(entityOnServer);

            console.log(entityOnServer);
        }
    };
};

function flyTo(lat, long) {
    viewer.camera.flyTo({
        destination: viewer.camera.flyTo(Cesium.Cartesian3.fromDegrees(long, lat, 100000)),
        orientation: {
            heading: Cesium.Math.toRadians(0.0),
            pitch: Cesium.Math.toRadians(-15.0),
        },
    });
}

function addEntity(entity) {
    let imgURL = "";
    let ajax = new XMLHttpRequest();
    let data = { id: entity.id };


    const position = Cesium.Cartesian3.fromDegrees(entity.longitude, entity.latitude, entity.altitude);

    let calunga = viewer.entities.add({
        position: position,
        id: entity.id,
        name: entity.matricula,
        description: entity.nome,
        label: {
            text: entity.matricula,
            font: "18px sans-serif",
            fillColor: Cesium.Color.YELLOW,
            outlineColor: Cesium.Color.BLACK,
            outlineWidth: 3,
            showBackground: true,
            style: Cesium.LabelStyle.FILL_AND_OUTLINE,
            horizontalOrigin: Cesium.HorizontalOrigin.CENTER,
            verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
            pixelOffset: new Cesium.Cartesian2(0.0, -50),
            disableDepthTestDistance: Number.POSITIVE_INFINITY,
        },
        billboard: {
            // scaleByDistance: new Cesium.NearFarScalar(1.5e2, 1.0, 1.5e4, 0.8),
            horizontalOrigin: Cesium.HorizontalOrigin.CENTER,
            verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
            disableDepthTestDistance: Number.POSITIVE_INFINITY,
        },
    });

    //  AJAX request to load image in base64 format from MySQL Database
    //  It was necessary to place the request here because the asynchronous javascript 
    //  would not load if it was placed before instantiating the maneuver part

    ajax.open("POST", "./source/controller/loadEntityImage.php", true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
            let respostaAjax = ajax.responseText;
            // console.log(`//11 ${respostaAjax}`);
            if (respostaAjax) {
                imgURL = 'data:image/png;base64,' + respostaAjax;
                // console.log(respostaAjax);
                calunga.billboard.image = imgURL;

                let i = new Image();
                i.src = imgURL;

                i.onload = function () {
                    calunga.billboard.width = i.width * 0.35;
                    calunga.billboard.height = i.height * 0.35;
                }
            }
        }
    }
    ajax.send(JSON.stringify(data));
}


function removeEntities(entities) {
    if (timeToCheckEntities) {

        let entitiesOnServer = entities;
        let entitiesOnCesium = viewer.entities.values;

        console.log("Verifing if entities are updated...")

        for (let i = 0; i < entitiesOnCesium.length; i++) {
            const entityOnCesium = entitiesOnCesium[i];

            let check = false;

            for (let j = 0; j < entitiesOnServer.length; j++) {
                const entityOnServer = entitiesOnServer[j];

                if (entityOnCesium.id == entityOnServer.id) {
                    console.log("//33");
                    check = true;
                    break;
                }
            }
            if (check == false) {
                console.log("//34");
                viewer.entities.removeById(entityOnCesium.id);
            }
        }
    }

    timeToCheckEntities = false;
}

// Criação da peça de manobra na tabela 'entidades' do banco de dados.
function createEntity(event) {

    // Encode the file using the FileReader API
    const reader = new FileReader();

    const imageElement = document.getElementById('createEntityImage').files[0];
    const nomeElement = document.getElementById('createEntityNome');
    const matriculaElement = document.getElementById('createEntityMatricula');
    const latitudeElement = document.getElementById('createEntityLatitude');
    const longitudeElement = document.getElementById('createEntityLongitude');
    const altitudeElement = document.getElementById('createEntityAltitude');

    let imgBlob;

    reader.readAsDataURL(imageElement);

    reader.onloadend = () => {
        imgBlob = reader.result
            .replace('data:', '')
            .replace(/^.+,/, '');

        let ajax = new XMLHttpRequest();

        let data = {
            nome: nomeElement.value,
            matricula: matriculaElement.value,
            latitude: latitudeElement.value,
            longitude: longitudeElement.value,
            altitude: altitudeElement.value,
            imagem: imgBlob,
        };

        ajax.open("POST", "./source/controller/createEntity.php", true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
                let respostaAjax = ajax.responseText;

                if (respostaAjax) {
                    console.log(respostaAjax);
                }

                $('#insertPointModal').modal('toggle');
            }
        }
        ajax.send(JSON.stringify(data));
    }
}

function moveEntity() {
    // Based on https://github.com/wanghongrui/cesium-entity-drag

    let entity = null
    let handler = null
    let moving = false

    let leftDownHandler = function (e) {
        entity = viewer.scene.pick(e.position);

        moving = true;
        if (entity) {
            viewer.scene.screenSpaceCameraController.enableRotate = false;
        }
    }

    let leftUpHandler = function (e) {
        const cartesian = viewer.camera.pickEllipsoid(e.position, viewer.scene.globe.ellipsoid);

        if (cartesian) {
            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);
            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude);

            let ajax = new XMLHttpRequest();

            let data = {
                id: entity.id.id,
                latitude: latitudeString,
                longitude: longitudeString,
            };

            ajax.open("POST", "./source/controller/updateEntityPosition.php", true);
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
                    let respostaAjax = ajax.responseText;
                    console.log(`//1 ${respostaAjax}`);
                }
            }
            ajax.send(JSON.stringify(data));
        }

        moving = false;
        entity = null;

        viewer.scene.screenSpaceCameraController.enableRotate = true;

        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_DOWN);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_UP);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    }

    let moveHandler = function (e) {
        if (moving && entity && entity.id) {
            const ray = viewer.camera.getPickRay(e.endPosition);
            const cartesian = scene.globe.pick(ray, scene);

            const ellipsoid = viewer.scene.globe.ellipsoid;
            const c = ellipsoid.cartesianToCartographic(cartesian);

            const origin = entity.id.position.getValue();
            const cc = ellipsoid.cartesianToCartographic(origin);

            entity.id.position = new Cesium.CallbackProperty(function () {
                return new Cesium.Cartesian3.fromRadians(c.longitude, c.latitude, cc.height)
            }, false);

            //entity.id.position = Cesium.Cartesian3.fromRadians(c.longitude, c.latitude, cc.height);
        }
    }

    let leftDown = leftDownHandler.bind();
    let leftUp = leftUpHandler.bind();
    let move = moveHandler.bind();

    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);

    handler.setInputAction(leftDown, Cesium.ScreenSpaceEventType.LEFT_DOWN);
    handler.setInputAction(leftUp, Cesium.ScreenSpaceEventType.LEFT_UP);
    handler.setInputAction(move, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
}

function insertPoint() {
    // Insert point like 'Peça de Manobra' 

    console.log("//32");

    //  let entity = null
    let handler = null
    let labelEntity = viewer.entities.add({
        label: {
            show: false,
            showBackground: true,
            font: "15px verdana",
            horizontalOrigin: Cesium.HorizontalOrigin.LEFT,
            verticalOrigin: Cesium.VerticalOrigin.TOP,
            pixelOffset: new Cesium.Cartesian2(15, 0),
            disableDepthTestDistance: Number.POSITIVE_INFINITY,
        },
    });

    let moveHandler = function (e) {
        // Mouse over the globe to see the cartographic position
        const cartesian = viewer.camera.pickEllipsoid(e.endPosition, scene.globe.ellipsoid);

        if (cartesian) {
            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);
            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude).toFixed(5);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude).toFixed(5);

            //console.log(`${latitudeString} ${longitudeString}`);

            labelEntity.position = cartesian;
            labelEntity.label.show = true;
            labelEntity.label.text =
                `Clique na posição\n` +
                `Latitude : ${`${latitudeString}`}\u00B0\n` +
                `Longitude: ${`${longitudeString}`.slice(-9)}\u00B0`;

            // console.log(labelEntity.label);

        } else {
            labelEntity.label.show = false;
        }
    };

    let leftClickHandler = function (e) {
        var pickedPosition = scene.pick(e.position);

        if (Cesium.defined(pickedPosition)) {
            alert('Local já possui um objeto');
            return;
        }

        // const cartesian = viewer.camera.pickEllipsoid(e.position, scene.globe.ellipsoid);
        const cartesian = scene.pickPosition(e.position);

        if (cartesian) {

            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);

            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude).toFixed(6);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude).toFixed(6);

            const altitude = cartographic.height;
            const altitudeString = Math.round(altitude).toString();

            console.log(altitudeString);

            $('#insertPointModal').modal();

            let textLatitude = document.getElementById("createEntityLatitude");
            let textLongitude = document.getElementById("createEntityLongitude");
            let textAltitude = document.getElementById("createEntityAltitude");

            textLatitude.value = latitudeString;
            textLongitude.value = longitudeString;
            textAltitude.value = altitudeString;
        }

        labelEntity.label.show = false;

        handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
    }

    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);

    handler.setInputAction(moveHandler, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    handler.setInputAction(leftClickHandler, Cesium.ScreenSpaceEventType.LEFT_CLICK);
}

// Layers
function showLayer(checkbox, range, layer) {
    let r = document.getElementById(range);
    let l = layers.get(layer);

    if (checkbox.checked == true) {
        l.show = true;
        r.hidden = false;
    } else {
        l.show = false;
        r.hidden = true;
    }
}

function rangeLayer(range, layer) {
    let l = layers.get(layer);
    l.alpha = range.value;
}

function showKML() {
    if (viewer.dataSources.length > 0) {
        viewer.dataSources.removeAll();
    }
    else {
        viewer.dataSources.add(rotas1);
    }
}

function showCPC() {
    if (viewer.dataSources.length > 0) {
        viewer.dataSources.removeAll();
    }
    else {
        viewer.dataSources.add(cpc2023);
    }
}

function showPaths() {
    let entitiesOnCesium = viewer.entities.values;

    for (let i = 0; i < entitiesOnCesium.length; i++) {
        let entity = entitiesOnCesium[i]

        if (entity.path.show == false) {
            entity.path.show = true;
        } else {
            entity.path.show = false;
        }
    }
}

// CZML File Upload
let inputFile = document.getElementById("fileUpload")

inputFile.addEventListener('change', function () {
    const file = this.files[0];
    const reader = new FileReader();

    if (file) {
        reader.readAsBinaryString(file);
    }

    reader.addEventListener('load', function () {

        let formData = new FormData();
        formData.append("file", file);
        fetch('upload.php', { method: "POST", body: formData });
        alert('Arquivo carregado com sucesso!');

        viewer.dataSources.add(Cesium.CzmlDataSource.load('upload/' + file.name));
    });

});

//  Rotina de checagem para remover as entidades que não estão no banco de dados.
//  Anteriormente essa rotina era colocada junto à rotina de atualização das peças de manobra,
//  mas foi necessário colocá-la aqui, temporariamente, para resolver o problema do navegador 
//  remover entidades que ainda não estavam salvas no banco de dados.
// 
//  Tempo setado em milisegundos - Ex: 5 min = 300 seg = 300.000 ms
setInterval(function () {
    timeToCheckEntities = true;
    timeToCheckMCC = true;
}, 60000);

// Helpers
// Carrega a imagem no modal de inserção de peças de manobras
function loadImage(event) {
    var output = document.getElementById('outputImage');
    var label = document.getElementById('createEntityImageLabel');

    output.src = URL.createObjectURL(event.target.files[0]);
    label.innerHTML = output.src;
    output.onload = function () {
        URL.revokeObjectURL(output.src)
    }
}

function drawFree() {
    let drawing = false;
    let color;
    let colors = [];
    let polyline;
    let positions = [];

    let data = {
        tipo_medida_coordenacao: 3, // No banco de dados, essa numeração refere-se ao tipo da medida POLIGONO
        coordenadas: [],
    };

    //  Based in https://cesium.com/blog/2016/03/21/drawing-on-the-globe-and-3d-models
    viewer.scene.globe.depthTestAgainstTerrain = true;

    let handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);


    let saveCoord = function (currentPositions) {
        console.log("AQUI");

        for (let i = 0; i < currentPositions.length; i++) {
            const cartographic = Cesium.Cartographic.fromCartesian(currentPositions[i]);

            data.coordenadas.push(Cesium.Math.toDegrees(cartographic.longitude));
            data.coordenadas.push(Cesium.Math.toDegrees(cartographic.latitude));
        }
    }

    let reset = function (color, currentPositions) {

        saveCoord(currentPositions);

        data.coordenadas = data.coordenadas.toString();

        // Apaga o vetor para outra linha livre
        positions = [];
        
        // Permite que o desenho seja mantido após desenhado
        draw(Cesium.Color.WHITE, currentPositions)

        // Realiza o salvento desse assincronamente na BD
        let ajax = new XMLHttpRequest();

        ajax.open("POST", "./source/controller/saveMCC.php", true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
                let respostaAjax = ajax.responseText;

                if (respostaAjax) {
                    console.log(respostaAjax);
                }
            }
        }
        ajax.send(JSON.stringify(data));
    }

    let draw = function (color, currentPositions) {

        console.log('drawFree', currentPositions);

        polyline = coordinationMeasuresDataSourceOnServer.entities.add({
            polyline: {
                positions: currentPositions,
                material: color,
                width: 4,
                clampToGround: true,
            }
        });
    }

    let pushColor = function (color, colors) {
        var lastColor = colors[colors.length - 1];
        if (!Cesium.defined(lastColor) || !color.equals(lastColor)) {
            colors.push(color);
        }
    }

    let moveHandler = function (e) {
        var pickedObject = scene.pick(e.endPosition);
        var length = colors.length;
        var lastColor = colors[length - 1];
        var cartesian = scene.pickPosition(e.endPosition);

        if (scene.pickPositionSupported && Cesium.defined(cartesian)) {
            var cartographic = Cesium.Cartographic.fromCartesian(cartesian);

            // are we drawing on the globe
            if (!Cesium.defined(pickedObject)) {
                color = Cesium.Color.WHITE;

                // if (!Cesium.defined(lastColor) || !lastColor.equals(Cesium.Color.BLUE)) {
                //     colors.push(Cesium.Color.BLUE);
                // }
                if (drawing) {
                    // if (Cesium.defined(lastColor) && lastColor.equals(Cesium.Color.BLUE)) {
                        positions.push(cartesian);
                    // } else {
                    //     //reset(lastColor, positions);
                    //     draw(color, positions);
                    // }
                }
            }
        }
    };

    let leftDownHandler = function (e) {
        viewer.scene.screenSpaceCameraController.enableRotate = false;
        
        polyline = viewer.entities.add({
            polyline: {
                positions: new Cesium.CallbackProperty(function () {
                    return positions;
                }, false),
                material: color,
                width: 3,
                clampToGround: true
            },
        });

        drawing = !drawing;
    };

    let leftUpHandler = function (e) {
        reset(color, positions);

        viewer.scene.screenSpaceCameraController.enableRotate = true;

        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_DOWN);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_UP);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);

        drawing = !drawing;
    };

    handler.setInputAction(moveHandler, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    handler.setInputAction(leftDownHandler, Cesium.ScreenSpaceEventType.LEFT_DOWN);
    handler.setInputAction(leftUpHandler, Cesium.ScreenSpaceEventType.LEFT_UP);
}

function dialogPolyline() {
    // console.log('Drawn now');

    let activeShapePoints = [];
    let activeShape;
    let floatingPoint;

    let data = {
        tipo_medida_coordenacao: 1,
        coordenadas: [],
    };

    let createButton = function () {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'cesium-button';
        button.onclick = function () {
            terminateShape();
            button.remove();


            handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
            handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);
            handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_DOUBLE_CLICK);
        };
        button.textContent = "Terminate Shape";
        document.getElementById('toolbar').appendChild(button);

    }

    let createPoint = function (worldPosition) {

        let cartographic = Cesium.Cartographic.fromCartesian(worldPosition);
        let latitude = Cesium.Math.toDegrees(cartographic.latitude);
        let longitude = Cesium.Math.toDegrees(cartographic.longitude);

        data.coordenadas.push(longitude, latitude);

        const point = viewer.entities.add({
            position: worldPosition,
            point: {
                color: Cesium.Color.YELLOW,
                pixelSize: 10,
                heightReference: Cesium.HeightReference.CLAMP_TO_GROUND,
            },
        });

        return point;
    }

    let drawShape = function (positionData) {

        // console.log('draw');
        let shape;
        shape = viewer.entities.add({
            polyline: {
                positions: positionData,
                clampToGround: true,
                width: 6,
                material: {
                    polylineOutline: {
                        color: {
                            rgba: [255, 165, 0, 255],
                        },
                        outlineColor: {
                            rgba: [0, 0, 0, 255],
                        },
                        outlineWidth: 2,
                    },
                },
            },
        });

        return shape;
    }

    let terminateShape = function () {
        activeShapePoints.pop();

        drawShape(activeShapePoints);

        viewer.entities.remove(floatingPoint);
        viewer.entities.remove(activeShape);

        floatingPoint = undefined;
        activeShape = undefined;
        activeShapePoints = [];

        data.coordenadas = data.coordenadas.toString();

        let ajax = new XMLHttpRequest();

        console.log(data);

        ajax.open("POST", "./source/controller/saveMCC.php", true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
                let respostaAjax = ajax.responseText;
                console.log(respostaAjax);
            }
        }

        ajax.send(JSON.stringify(data));
    }

    let moveHandler = function (event) {
        if (Cesium.defined(floatingPoint)) {
            const ray = viewer.camera.getPickRay(event.endPosition);
            const newPosition = viewer.scene.globe.pick(ray, viewer.scene);

            if (Cesium.defined(newPosition)) {
                floatingPoint.position.setValue(newPosition);
                activeShapePoints.pop();
                activeShapePoints.push(newPosition);
            }
        }
    };

    let leftClickHandler = function (event) {
        const ray = viewer.camera.getPickRay(event.position);
        const earthPosition = viewer.scene.globe.pick(ray, viewer.scene);

        if (Cesium.defined(earthPosition)) {
            if (activeShapePoints.length === 0) {
                floatingPoint = createPoint(earthPosition);
                activeShapePoints.push(earthPosition);
                const dynamicPositions = new Cesium.CallbackProperty(function () {
                    return activeShapePoints;
                }, false);

                activeShape = drawShape(dynamicPositions);
            }
            activeShapePoints.push(earthPosition);

            createPoint(earthPosition);
        }

    }

    let leftDoubleClickHandler = function (e) {
        terminateShape();

        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_DOUBLE_CLICK);
    };

    createButton();

    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);

    handler.setInputAction(moveHandler, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    handler.setInputAction(leftClickHandler, Cesium.ScreenSpaceEventType.LEFT_CLICK);
    handler.setInputAction(leftDoubleClickHandler, Cesium.ScreenSpaceEventType.LEFT_DOUBLE_CLICK);
}

function measurementPoints() {
    // Cria uma medição entre dois pontos no globo
    // Ao ser chamada, a função permite a inserção de dois pontos para medição de distância.
    //
    // 1º Click - Insere o primeiro ponto
    // 2º Click - Insere o segundo ponto
    // 3º Click - Apaga a medição

    var ellipsoid = Cesium.Ellipsoid.WGS84;
    var geodesic = new Cesium.EllipsoidGeodesic();

    var points = scene.primitives.add(new Cesium.PointPrimitiveCollection());
    var point1, point2;
    var point1GeoPosition, point2GeoPosition;
    var polylines = scene.primitives.add(new Cesium.PolylineCollection());
    var polyline1, polyline2, polyline3;
    var distanceLabel, verticalLabel, horizontalLabel;
    var LINEPOINTCOLOR = Cesium.Color.RED;
    // var labels = scene.primitives.add(new Cesium.LabelCollection({scene: scene}));


    var label = {
        font: '16px monospace',
        showBackground: true,
        horizontalOrigin: Cesium.HorizontalOrigin.CENTER,
        verticalOrigin: Cesium.VerticalOrigin.BOTTOM,
        pixelOffset: new Cesium.Cartesian2(0, 0),
        eyeOffset: new Cesium.Cartesian3(0, 0, -50),
        fillColor: Cesium.Color.WHITE,
    };

    let addDistanceLabel = function (p1, p2, height) {
        point1.cartographic = ellipsoid.cartesianToCartographic(p1.position);
        point2.cartographic = ellipsoid.cartesianToCartographic(p2.position);
        point1.longitude = parseFloat(Cesium.Math.toDegrees(p1.position.x));
        point1.latitude = parseFloat(Cesium.Math.toDegrees(p1.position.y));
        point2.longitude = parseFloat(Cesium.Math.toDegrees(p2.position.x));
        point2.latitude = parseFloat(Cesium.Math.toDegrees(p2.position.y));

        label.text = getHorizontalDistanceString(point1, point2);

        horizontalLabel = viewer.entities.add({
            position: getMidpoint(point1, point2, point1GeoPosition.height + 5),
            label: label
        });

        label.text = getDistanceString(point1, point2);

        distanceLabel = viewer.entities.add({
            position: getMidpoint(point1, point2, height + 5),
            label: label
        });

        label.text = getVerticalDistanceString();

        verticalLabel = viewer.entities.add({
            position: getMidpoint(point2, point2, height + 5),
            label: label
        });
    };

    let getHorizontalDistanceString = function (point1, point2) {
        geodesic.setEndPoints(point1.cartographic, point2.cartographic);
        var meters = geodesic.surfaceDistance.toFixed(2);

        console.log("Horizontal distance: " + meters);

        if (meters >= 1000) {
            return (meters / 1000).toFixed(3) + ' км';
        }

        return meters + ' м';
    };

    let getVerticalDistanceString = function () {
        var heights = [point1GeoPosition.height, point2GeoPosition.height];
        var meters = Math.max.apply(Math, heights) - Math.min.apply(Math, heights);

        console.log("Vertical distance: " + meters);

        if (meters >= 1000) {
            return (meters / 1000).toFixed(3) + ' км';
        }

        return meters.toFixed(2) + ' м';
    };

    let getDistanceString = function (point1, point2) {
        geodesic.setEndPoints(point1.cartographic, point2.cartographic);
        var horizontalMeters = geodesic.surfaceDistance.toFixed(2);
        var heights = [point1GeoPosition.height, point2GeoPosition.height];
        var verticalMeters = Math.max.apply(Math, heights) - Math.min.apply(Math, heights);
        var meters = Math.pow((Math.pow(horizontalMeters, 2) + Math.pow(verticalMeters, 2)), 0.5);

        console.log("Distance: " + meters);

        if (meters >= 1000) {
            return (meters / 1000).toFixed(3) + ' км';
        }

        return meters.toFixed(2) + ' м';
    };

    let getMidpoint = function (point1, point2, height) {
        var scratch = new Cesium.Cartographic();
        geodesic.setEndPoints(point1.cartographic, point2.cartographic);
        var midpointCartographic = geodesic.interpolateUsingFraction(0.5, scratch);
        return Cesium.Cartesian3.fromRadians(midpointCartographic.longitude, midpointCartographic.latitude, height);
    };

    let leftClickHandler = function (click) {
        if (scene.mode !== Cesium.SceneMode.MORPHING) {

            var cartesian = viewer.scene.pickPosition(click.position);

            if (Cesium.defined(cartesian)) {
                if (points.length === 2) {

                    handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
                    polylines.removeAll();
                    viewer.entities.remove(distanceLabel);
                    viewer.entities.remove(horizontalLabel);
                    viewer.entities.remove(verticalLabel);
                    points.removeAll();

                    handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
                }
                //add first point
                else if (points.length === 0) {
                    point1 = points.add({
                        position: new Cesium.Cartesian3(cartesian.x, cartesian.y, cartesian.z),
                        color: LINEPOINTCOLOR
                    });
                } //add second point and lines
                else if (points.length === 1) {
                    point2 = points.add({
                        position: new Cesium.Cartesian3(cartesian.x, cartesian.y, cartesian.z),
                        color: LINEPOINTCOLOR
                    });

                    point1GeoPosition = Cesium.Cartographic.fromCartesian(point1.position);
                    point2GeoPosition = Cesium.Cartographic.fromCartesian(point2.position);
                    // point3GeoPosition = Cesium.Cartographic.fromCartesian(new Cesium.Cartesian3(point2.position.x, point2.position.y, point1.position.z));

                    var pl1Positions = [
                        new Cesium.Cartesian3.fromRadians(point1GeoPosition.longitude, point1GeoPosition.latitude, point1GeoPosition.height),
                        new Cesium.Cartesian3.fromRadians(point2GeoPosition.longitude, point2GeoPosition.latitude, point2GeoPosition.height)
                    ];
                    var pl2Positions = [
                        new Cesium.Cartesian3.fromRadians(point2GeoPosition.longitude, point2GeoPosition.latitude, point2GeoPosition.height),
                        new Cesium.Cartesian3.fromRadians(point2GeoPosition.longitude, point2GeoPosition.latitude, point1GeoPosition.height)
                    ];
                    var pl3Positions = [
                        new Cesium.Cartesian3.fromRadians(point1GeoPosition.longitude, point1GeoPosition.latitude, point1GeoPosition.height),
                        new Cesium.Cartesian3.fromRadians(point2GeoPosition.longitude, point2GeoPosition.latitude, point1GeoPosition.height)
                    ];

                    polyline1 = polylines.add({
                        show: true,
                        positions: pl1Positions,
                        width: 2,
                        material: new Cesium.Material({
                            fabric: {
                                type: 'Color',
                                uniforms: {
                                    color: LINEPOINTCOLOR,
                                }
                            }
                        }),
                        depthFailMaterial: new Cesium.PolylineOutlineMaterialProperty({
                            color: Cesium.Color.YELLOW,
                        }),
                    });

                    polyline2 = polylines.add({
                        show: true,
                        positions: pl2Positions,
                        width: 1,
                        material: new Cesium.Material({
                            fabric: {
                                type: 'PolylineDash',
                                uniforms: {
                                    color: LINEPOINTCOLOR,
                                }
                            },
                        }),
                        depthFailMaterial: new Cesium.PolylineOutlineMaterialProperty({
                            color: Cesium.Color.YELLOW,
                        }),
                    });

                    polyline3 = polylines.add({
                        show: true,
                        positions: pl3Positions,
                        width: 1,
                        material: new Cesium.Material({
                            fabric: {
                                type: 'PolylineDash',
                                uniforms: {
                                    color: LINEPOINTCOLOR,
                                }
                            },
                        }),
                        depthFailMaterial: new Cesium.PolylineOutlineMaterialProperty({
                            color: Cesium.Color.YELLOW,
                        }),
                    });

                    var labelZ;

                    if (point2GeoPosition.height >= point1GeoPosition.height) {
                        labelZ = point1GeoPosition.height + (point2GeoPosition.height - point1GeoPosition.height) / 2.0;
                    } else {
                        labelZ = point2GeoPosition.height + (point1GeoPosition.height - point2GeoPosition.height) / 2.0;
                    };

                    addDistanceLabel(point1, point2, labelZ);
                }
            }
        }
    };

    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);

    handler.setInputAction(leftClickHandler, Cesium.ScreenSpaceEventType.LEFT_CLICK);
}

function insertCilinder() {
    // Insert point like 'Peça de Manobra' 

    console.log("//37");

    //  let entity = null
    let handler = null
    let labelEntity = viewer.entities.add({
        label: {
            show: false,
            showBackground: true,
            font: "15px verdana",
            horizontalOrigin: Cesium.HorizontalOrigin.LEFT,
            verticalOrigin: Cesium.VerticalOrigin.TOP,
            pixelOffset: new Cesium.Cartesian2(15, 0),
            disableDepthTestDistance: Number.POSITIVE_INFINITY,
        },
    });

    let moveHandler = function (e) {
        // Mouse over the globe to see the cartographic position
        const cartesian = viewer.camera.pickEllipsoid(e.endPosition, scene.globe.ellipsoid);

        if (cartesian) {
            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);
            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude).toFixed(5);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude).toFixed(5);

            //console.log(`${latitudeString} ${longitudeString}`);

            labelEntity.position = cartesian;
            labelEntity.label.show = true;
            labelEntity.label.text =
                `Selecione a posição\n` +
                `Latitude : ${`${latitudeString}`}\u00B0\n` +
                `Longitude: ${`${longitudeString}`.slice(-9)}\u00B0`;

            // console.log(labelEntity.label);

        } else {
            labelEntity.label.show = false;
        }
    };

    let leftClickHandler = function (e) {
        var pickedPosition = scene.pick(e.position);

        if (Cesium.defined(pickedPosition)) {
            alert('Local já possui um objeto');
            return;
        }

        // const cartesian = viewer.camera.pickEllipsoid(e.position, scene.globe.ellipsoid);
        const cartesian = scene.pickPosition(e.position);

        if (cartesian) {

            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);

            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude).toFixed(6);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude).toFixed(6);

            const altitude = cartographic.height;
            const altitudeString = Math.round(altitude).toString();

            console.log(altitudeString);

            $('#insertCylinder').modal();

            let textLatitude = document.getElementById("createEntityLatitude");
            let textLongitude = document.getElementById("createEntityLongitude");
            let textAltitude = document.getElementById("createEntityAltitude");

            textLatitude.value = latitudeString;
            textLongitude.value = longitudeString;
            textAltitude.value = altitudeString;
        }

        labelEntity.label.show = false;

        handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
    }

    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);

    handler.setInputAction(moveHandler, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    handler.setInputAction(leftClickHandler, Cesium.ScreenSpaceEventType.LEFT_CLICK);
}

function dialogPolygon() {

    console.log("//39");

    //  let entity = null
    let handler = null
    let labelEntity = viewer.entities.add({
        label: {
            show: false,
            showBackground: true,
            font: "15px verdana",
            horizontalOrigin: Cesium.HorizontalOrigin.LEFT,
            verticalOrigin: Cesium.VerticalOrigin.TOP,
            pixelOffset: new Cesium.Cartesian2(15, 0),
            disableDepthTestDistance: Number.POSITIVE_INFINITY,
        },
    });

    let moveHandler = function (e) {
        // Mouse over the globe to see the cartographic position
        const cartesian = viewer.camera.pickEllipsoid(e.endPosition, scene.globe.ellipsoid);

        if (cartesian) {
            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);
            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude).toFixed(5);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude).toFixed(5);

            //console.log(`${latitudeString} ${longitudeString}`);

            labelEntity.position = cartesian;
            labelEntity.label.show = true;
            labelEntity.label.text =
                `Clique na posição\n` +
                `Latitude : ${`${latitudeString}`}\u00B0\n` +
                `Longitude: ${`${longitudeString}`.slice(-9)}\u00B0`;

            // console.log(labelEntity.label);

        } else {
            labelEntity.label.show = false;
        }
    };

    let leftClickHandler = function (e) {
        var pickedPosition = scene.pick(e.position);

        // if (Cesium.defined(pickedPosition)) {
        //     alert('Local já possui um objeto');
        //     return;
        // }

        // const cartesian = viewer.camera.pickEllipsoid(e.position, scene.globe.ellipsoid);
        const cartesian = scene.pickPosition(e.position);

        if (cartesian) {

            const cartographic = Cesium.Cartographic.fromCartesian(cartesian);

            const longitudeString = Cesium.Math.toDegrees(cartographic.longitude).toFixed(6);
            const latitudeString = Cesium.Math.toDegrees(cartographic.latitude).toFixed(6);

            const altitude = cartographic.height;

            $('#insertPolygonModal').modal();

            let textLatitude = document.getElementById("insertPolygonLatitude");
            let textLongitude = document.getElementById("insertPolygonLongitude");
            let textAltitude = document.getElementById("insertPolygonAltitude");

            textLatitude.value = latitudeString;
            textLongitude.value = longitudeString;

        }

        labelEntity.label.show = false;

        handler.removeInputAction(Cesium.ScreenSpaceEventType.MOUSE_MOVE);
        handler.removeInputAction(Cesium.ScreenSpaceEventType.LEFT_CLICK);
    }

    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);

    handler.setInputAction(moveHandler, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    handler.setInputAction(leftClickHandler, Cesium.ScreenSpaceEventType.LEFT_CLICK);
}

function savePolygon(event) {

    const nomeElement = document.getElementById('insertPolygonNome');
    const verticesElement = document.getElementById('insertPolygonVertices');
    const raioElement = document.getElementById('insertPolygonRaio');
    const corElement = document.getElementById('insertPolygonColor');
    const latitudeElement = document.getElementById('insertPolygonLatitude');
    const longitudeElement = document.getElementById('insertPolygonLongitude');
    const alturaElement = document.getElementById('insertPolygonAltura');
    const altitudeElement = document.getElementById('insertPolygonAltitude');
    const fixarSoloElement = document.getElementById('insertPolygonFixarSolo');
    const azimuteElement = document.getElementById('insertPolygonAzimute');


    let data = {
        tipo_medida_coordenacao: 2, // No banco de dados, essa numeração refere-se ao tipo da medida POLIGONO
        nome: nomeElement.value,
        vertices: verticesElement.value,
        raio: raioElement.value / 1000,
        cor: corElement.value,
        latitude: parseFloat(latitudeElement.value),
        longitude: parseFloat(longitudeElement.value),
        azimute: azimuteElement.value,
        altura: alturaElement.value,
        altitude: altitudeElement.value,
        fixarSolo: fixarSoloElement.checked,
        coordenadas: [],
    };

    // Cálculo de coordenadas dos pontos do polígono
    let getPolygonCoordinates = function (lat, long, raioKm, numeroVertices, azimute) {
        let radiusLon = 1 / (111.319 * Math.cos(lat * (Math.PI / 180))) * raioKm;
        let radiusLat = 1 / 110.574 * raioKm;
        let dTheta = 2 * Math.PI / numeroVertices;
        let theta = (Math.PI / 2) - (azimute * (Math.PI / 180)); // Alterado de 0 para PI/180 a fim de garantir o primeiro ponto no Norte Geográfico menos o azimute desejado pelo usuário.
        let points = []

        for (let i = 0; i < numeroVertices; i++) {
            points.push(parseFloat(long) + radiusLon * Math.cos(theta))
            points.push(parseFloat(lat) + radiusLat * Math.sin(theta));

            theta += dTheta
        }
        return points;
    }

    data.coordenadas = getPolygonCoordinates(data.latitude.toFixed(6), data.longitude.toFixed(6), data.raio, data.vertices, data.azimute);

    console.log(data);
    console.log(Cesium.Cartesian3.fromDegreesArray(data.coordenadas));

    // let polygon = coordinationMeasuresDataSourceOnServer.entities.add({
    //     position: Cesium.Cartesian3.fromDegrees(data.longitude, data.latitude),
    //     orientation: Cesium.HeadingPitchRoll(
    //         Cesium.Math.toRadians(data.azimute), 0, 0),
    //     polygon: {
    //         hierarchy: Cesium.Cartesian3.fromDegreesArray(data.coordenadas),
    //         material: Cesium.Color.fromCssColorString(data.cor).withAlpha(0.5),
    //         height: parseFloat(data.altura) + parseFloat(data.altitude),
    //         heightReference: Cesium.HeightReference.RELATIVE_TO_GROUND,
    //         extrudedHeight: data.altitude,
    //         extrudedHeightReference: Cesium.HeightReference.RELATIVE_TO_GROUND,
    //         closeTop: true,
    //         closeBottom: true,
    //     }
    // });

    if (data.fixarSolo == true) {
        data.fixarSolo = 1;
    } else {
        data.fixarSolo = 0;
    }

    data.coordenadas = data.coordenadas.toString();

    console.log(data);

    let ajax = new XMLHttpRequest();

    ajax.open("POST", "./source/controller/saveMCC.php", true);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
            let respostaAjax = ajax.responseText;

            if (respostaAjax) {
                console.log(respostaAjax);
            }

            $('#insertPolygonModal').modal('toggle');
        }
    }
    ajax.send(JSON.stringify(data));
}

//  Connect to socket 
connectSocket();



// Interaction 
// Context Menu
let contextMenu = function () {
    let c = {};
    function t(e) {
        c = e;
        let t = document.createElement("div");
        t.classList.add(c.className || "contextMenu"),
            t.setAttribute("id", c.id || "contextMenuId"),
            o(t, { position: "fixed", display: "none", top: "0px", left: "0px" });
        let i = document.createElement("ul");
        o(i, {
            listStyle: "none",
            padding: "0px",
            margin: "0px",
            display: "flex",
            flexDirection: "column",
        }),
            c.items.forEach((t, n) => {
                let e = document.createElement("li");
                (e.innerHTML = t.template),
                    e.classList.add("contextMenuItem"),
                    o(e, { cursor: "pointer" }),
                    e.addEventListener("click", function (e) {
                        t.onClick(e.target, n);
                    }),
                    i.classList.add("contextMenuList"),
                    i.appendChild(e);
            }),
            t.appendChild(i),
            document.body.appendChild(t);
    }
    function o(t, n) {
        Object.keys(n).forEach((e) => {
            t.style[e] = n[e];
        });
    }
    return (
        (t.prototype.init = function (e) {
            let t = e || document;
            e = c.id || "contextMenuId";
            let n = document.querySelector("#" + e);
            n.addEventListener("mouseleave", function () {
                o(this, { display: "none" });
            }),
                t.addEventListener("contextmenu", function (e) {
                    e.preventDefault(),
                        o(n, {
                            top: e.clientY + "px",
                            left: e.clientX + "px",
                            display: "block",
                        });
                });
        }),
        function (e) {
            return new t(e);
        }
    );
}();

function rightClickScene() {
    contextMenu({
        items: [
            {
                template: "<a href='#'>Adicionar uma peça de manobra aqui</a>",
                onClick: function (item, index) {
                    var modal = document.getElementById("myModal");
                    modal.style.display = "block";
                }
            },
            {
                template: "<a href='#'>Facebook</a>",
                onClick: function (item, index) {
                    console.log("you have clicked on Facebook link !!!!!!")
                }
            },
            {
                template: "<a href='#'>Youtube</a>",
                onClick: function (item, index) {
                    console.log("you have clicked on Youtube link !!!!!!")
                }
            }
        ]
    }).init(document.getElementById("contextMenu"));
}

function rightClicVRDAE() {
    contextMenu({
        items: [
            {
                template: "<a href='#'>Github</a>",
                onClick: function (item, index) {
                    console.log("you have clicked on VRDAE link !!!!!!")
                }
            },
        ]
    }).init(document.getElementById("contextMenu"));
}


function leftClickDefault(e) {

    let divMenuContainer = document.createElement('div');
    divMenuContainer.type = 'div';
    divMenuContainer.id = 'divMenuContainer';

    let removeDivCotainer = function () {
        if (document.getElementById('divMenuContainer')) {
            document.getElementById('divMenuContainer').remove();
        }
    }

    let createCardsMenu = function (e) {

        removeDivCotainer();

        let delButton = document.createElement('button');

        delButton.type = 'button';
        delButton.className = 'cesium-button';
        delButton.textContent = `Deletar Peça ${e.id}`;

        delButton.onclick = function () {
            divMenuContainer.remove();
        };

        divMenuContainer.appendChild(delButton)
        document.getElementById('toolbar').appendChild(divMenuContainer);
    }




    var pickedPosition = scene.pick(e.position);

    if (Cesium.defined(pickedPosition)) {

        if (pickedPosition.id.isMCC == true) {       // Se a propriedade isMCC estiver definida como TRUE, as opções deverão direcionadas para o DataSource que contém as Medidas de Coordenação 'coordinationMeasuresDataSourceOnServer'
            console.log('É desenho!');
            console.log(pickedPosition.id.id);

            removeDivCotainer();

        } else {                                    // Caso contrário, as opções deverão ser direcionadas para o DataSource que contém as peças de manobra: cardsMeasuresDataSource
            console.log('É peça de manobra!');
            console.log(pickedPosition.id.id);

            createCardsMenu(pickedPosition.id)
        }
        return;
    } else {
        console.log('Não está definido!');

        removeDivCotainer();
    }


}

handler.setInputAction(leftClickDefault, Cesium.ScreenSpaceEventType.LEFT_CLICK);

// Habilita o controle de navegação e zoom
// https://github.com/3DGISKing/CesiumJsSamples/tree/master/Mixins/NavigationMixin
viewer.extend(Cesium.viewerCesiumNavigationMixin, {});


function loadEntities() {
    let ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status >= 200 && ajax.status <= 400) {
            let respostaAjax = ajax.responseText;

            // console.log(respostaAjax);

            let entities = JSON.parse(respostaAjax);

          for (let i = 0; i < entities.length; i++) {
            const entity = entities[i];

            // console.log(entity);
            
          }
            // console.log(JSON.parse(respostaAjax));
        }
    }

    ajax.open("GET", "./source/controller/loadEntities.php", true);
    ajax.send();
}

loadEntities();