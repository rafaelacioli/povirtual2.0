const socket = new WebSocket("ws://192.168.0.111:3000")

socket.addEventListener("open", () => {
  console.log("We are connected");
});

socket.onmessage = function (e) {
  let data = e.data;
  let entities = JSON.parse(data);


  updateEntities(entities);
};

function updateEntities(entities) {
  let entitiesOnServer = entities;
  let entitiesOnCesium = viewer.entities.values;

  for (let i = 0; i < entitiesOnServer.length; i++) {
    const entityOnServer = entitiesOnServer[i];

    let check = false;

    for (let j = 0; j < entitiesOnCesium.length; j++) {
      let entityOnCesium = entitiesOnCesium[j];

      if (entityOnServer.id == entityOnCesium.id) {
        check = true;

        entityOnCesium.position = Cesium.Cartesian3.fromDegrees(entityOnServer.longitude, entityOnServer.latitude, entityOnServer.altitude);
        entityOnCesium.description = entityOnServer.longitude;

      }
    }

    if (check == false) {
      addEntity(entityOnServer);
    };
  };

  for (let i = 0; i < entitiesOnCesium.length; i++) {
    const entityOnCesium = entitiesOnCesium[i];

    let check = false;

    for (let j = 0; j < entitiesOnServer.length; j++) {
      const entityOnServer = entitiesOnServer[j];

      if (entityOnCesium.id == entityOnServer.id) {
        check = true;
      }

    }
    if (check == false) {
      viewer.entities.removeById(entityOnCesium.id);
    }
  }
};

function addEntity(entity) {
  viewer.entities.add({
    position: Cesium.Cartesian3.fromDegrees(entity.longitude, entity.latitude, entity.altitude),
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
      pixelOffset: new Cesium.Cartesian2(0.0, -40),
    },
    billboard: {
      image: "media/anv.png",
      width: 43,
      height: 50,
      translucencyByDistance: new Cesium.NearFarScalar(1.5e2, 2.0, 1.5e7, 0.5),
    },
    path: {
      material: {
        polylineOutline: {
          color: {
            rgba: [255, 0, 255, 255],
          },
          outlineColor: {
            rgba: [0, 255, 255, 255],
          },
          outlineWidth: 5,
        },
      },
      width: 8,
      show: true,
      trailTime: 60,
    },
  });
  console.log("Entity added")
}

function addPath(entity){

}