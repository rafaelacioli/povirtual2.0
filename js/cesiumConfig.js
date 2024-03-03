Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJlNjc1MmFiNy00NTEyLTRlYzgtOWRhMi02M2U0ZmMzMmE2MDciLCJpZCI6MTA3NDYyLCJpYXQiOjE2NjQ2MzYxNzh9.tFjf2tV8S9AzEOVdTSL6kqXTkT_8mly-YsLHwMUaYGY';

// Initialize the Cesium Viewer in the HTML element with the `cesiumContainer` ID.
const viewer = new Cesium.Viewer('cesiumContainer', {
    terrainProvider: Cesium.createWorldTerrain(),
    timeline: false,
    animation: false,
    infoBox: true,
    fullscreenButton: false,
    geocoder: false,
    homeButton: false,
});

viewer.camera.flyTo({
    destination: Cesium.Cartesian3.fromDegrees(-55.37, -12.86, 10000000),
    // orientation : {
    //   heading : Cesium.Math.toRadians(0.0),
    //   pitch : Cesium.Math.toRadians(-15.0),
    // }
});

// Add Cesium OSM Buildings, a global 3D buildings layer.
// const buildingTileset = viewer.scene.primitives.add(Cesium.createOsmBuildings());

let scene = viewer.scene;
let layers = viewer.scene.imageryLayers;
let handler = new Cesium.ScreenSpaceEventHandler(viewer.canvas);

let floatingPoint;
let activeShapePoints = [];



// Layers

// Add BDGEx 1:25.000 to index 1
const bdgex1 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm25",
    enablePickFeatures: false,
});

// Add BDGEx 1:50.000 to index 2
const bdgex2 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm50",
    enablePickFeatures: false,
});

// Add BDGEx 1:100.000 to index 2
const bdgex3 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm100",
    enablePickFeatures: false,
});

// Add BDGEx 1:250.000 to index 2
const bdgex4 = new Cesium.WebMapServiceImageryProvider({
    url: "https://bdgex.eb.mil.br/mapcache",
    layers: "ctm250",
    enablePickFeatures: false,
});


layers.addImageryProvider(bdgex1, 1);
layers.addImageryProvider(bdgex2, 2);
layers.addImageryProvider(bdgex3, 3);
layers.addImageryProvider(bdgex4, 4);

layers.get(1).show = false;
layers.get(2).show = false;
layers.get(3).show = false;
layers.get(4).show = false;


// Drawing

function createPoint(worldPosition) {
    point = viewer.entities.add({
        position: worldPosition,
        point: {
            color: Cesium.Color.WHITE,
            pixelSize: 10,
            heightReference: Cesium.HeightReference.CLAMP_TO_GROUND + 10,
        }
    });

    return point;
}

function drawVRDAE() {
    handler.setInputAction(function (event) {
        var clickedPosition = scene.pickPosition(event.position);
        var pickedObject = scene.pick(event.position);
        let radius = 6000.0;

        if (Cesium.defined(pickedObject)) {
            alert('Local já possui um objeto');
            return;
        }

        viewer.entities.add({
            position: clickedPosition,
            ellipsoid: {
                radii: new Cesium.Cartesian3(radius, radius, radius),
                maximumCone: Cesium.Math.PI_OVER_TWO,
                id: 1,
                material: new Cesium.GridMaterialProperty({
                    color: Cesium.Color.RED,
                    cellAlpha: 0.4,
                    lineCount: new Cesium.Cartesian2(8, 8),
                    lineThickness: new Cesium.Cartesian2(1.0, 1.0),
                }),
                outline: false,
            },
        });
        console.log(clickedPosition);

    }, Cesium.ScreenSpaceEventType.LEFT_CLICK);

}




handler.setInputAction(function (event) {
    var pickedObject = scene.pick(event.position);

    // if (Cesium.defined(pickedObject)) {
    //     let id = pickedObject.id["id"]

    //     let entity = viewer.entities.getById(id);

    //     entity.ellipsoid.material.color = Cesium.Color.fromRandom({ alpha: 0.8 });
    //     console.log(entity.ellipsoid.material.color);
    // }


    rightClickScene();
}, Cesium.ScreenSpaceEventType.RIGHT_CLICK);


function mouseMovement() {
    const entity = viewer.entities.add({
        label: {
            show: false,
            showBackground: true,
            font: "14px monospace",
            horizontalOrigin: Cesium.HorizontalOrigin.LEFT,
            verticalOrigin: Cesium.VerticalOrigin.TOP,
            pixelOffset: new Cesium.Cartesian2(15, 0),
        },
    });

    // Mouse over the globe to see the cartographic position
    handler = new Cesium.ScreenSpaceEventHandler(scene.canvas);
    handler.setInputAction(function (movement) {
        const cartesian = viewer.camera.pickEllipsoid(
            movement.endPosition,
            scene.globe.ellipsoid
        );
        if (cartesian) {
            const cartographic = Cesium.Cartographic.fromCartesian(
                cartesian
            );
            const longitudeString = Cesium.Math.toDegrees(
                cartographic.longitude
            ).toFixed(2);
            const latitudeString = Cesium.Math.toDegrees(
                cartographic.latitude
            ).toFixed(2);

            entity.position = cartesian;
            entity.label.show = true;
            entity.label.text =
                `Lon: ${`   ${longitudeString}`.slice(-7)}\u00B0` +
                `\nLat: ${`   ${latitudeString}`.slice(-7)}\u00B0`;
        } else {
            entity.label.show = false;
        }
    }, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
}

