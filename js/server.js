const WebSocket = require("ws"); // https://github.com/websockets/ws#simple-server
const mysql = require('mysql');
const http = require('http');
// const crypt = require('crypto');

const url = "http://186.250.70.138:8080/mesatatica/";

const PORT = 3000;
const wss = new WebSocket.Server({ port: PORT });

// Lista de simuladores XPlane configurados em base de dados
let simulators = [];

// Configurando conexão com Banco de Dados
const db = mysql.createConnection({
    host: '127.0.0.1',
    user: 'mesatatica',
    password: 'povirtual2023',
    database: 'pov',
    port: 3306
});

// Boas-vindas
console.log(`🚁🪖 POVIRTUAL v1.1.4\n--`);

connectDatabase();

db.query("SELECT * FROM `entidades` WHERE `tipo` = 4;", function (err, result) {
    if (err) throw err;

    if (result.length === 0) {
        // console.log(err);
    } else {
        simulators = [];

        for (let i = 0; i < result.length; i++) {
            // console.log(result[i]["matricula"] + " " + result[i]["ipv4"] + " " + parseInt(result[i]["porta"]));
            let connXplane = require('./xplane').XPlaneConnection(result[i]["matricula"], result[i]["ipv4"], parseInt(result[i]["porta"]));
            connXplane.setID(result[i]["id"]);

            simulators.push(connXplane);
        }
    }

    console.log(`🕹️ Simuladores configurados na base: ${simulators.length}\n--`);
});

// Obtendo simuladores configurados
getSimulators();

// Websocket configuration
wss.on("connection", function connection(ws, request) {

    let sendInterval;

    ws.isAlive = true;

    console.log(`🔗 Novo cliente conectado. IP: ${request.socket.remoteAddress}`);


    // Quando a linha abaixo está habilitada, atualiza as posições com o servidor Sentinela AvEx
    saveEntities();

    ws.on("close", () => {
        console.log(`🔴 Cliente IP: ${request.socket.remoteAddress} desconectou.\n--`);
        accessLog(2, request.socket.remoteAddress);
        ws.isAlive = false;
        ws.terminate();
        clearInterval(sendInterval);
    });

    ws.on('message', function (data) {
        // Recebe uma mensagem do cliente no formato JSON
        // {
        //    code: ""
        //    message: ""  
        // }
        let wsMessage = JSON.parse(data.toString());

        // console.log(wsMessage);

        if (wsMessage.code == "getEntities") {
            let response = getEntities();

            setTimeout(() => {
                ws.send(JSON.stringify(response));
            }, 5000);
        }

        if (wsMessage.code == "getRealTimeEntities") {


            console.log(`🔵 Enviando informações para o usuário ${wsMessage.username} em ${request.socket.remoteAddress}`);

            accessLog(1, request.socket.remoteAddress, wsMessage.message);

            sendInterval = setInterval(() => {
                getRealTimeEntites(ws, wsMessage.userid)
            }, 1000);
        }

        if (wsMessage.code == "getAllRealTimeEntities") {
            console.log(`🔵 Enviando informações para o visualizador ${wsMessage.username} em ${request.socket.remoteAddress}`);

            accessLog(1, request.socket.remoteAddress, wsMessage.message);

            sendInterval = setInterval(() => {
                getAllRealTimeEntites(ws)
            }, 1000);
        }

    });
});

function getSimulators() {
    setInterval(() => {
        db.query("SELECT * FROM `entidades` WHERE `tipo` = 4;", function (err, result) {
            if (err) throw err;

            if (result.length === 0) {
                // console.log(err);
            } else {
                simulators = [];

                for (let i = 0; i < result.length; i++) {
                    // console.log(result[i]["matricula"] + " " + result[i]["ipv4"] + " " + parseInt(result[i]["porta"]));
                    let connXplane = require('./xplane').XPlaneConnection(result[i]["matricula"], result[i]["ipv4"], parseInt(result[i]["porta"]));
                    connXplane.setID(result[i]["id"]);

                    simulators.push(connXplane);
                }
            }
        });
    }, 30000);
    
    // console.log(`🕹️ Simuladores configurados na base: ${simulators.length}`);
}

function getEntities() {
    let response = [];

    db.query("SELECT * FROM `medidas_coordenacao`", async function (err, result) {
        if (err) throw err;

        if (result.length === 0) {
            console.log(err);
        }
        else {
            let data = [];
            let coordinationMeasures = JSON.parse(JSON.stringify(await result));

            for (let i = 0; i < coordinationMeasures.length; i++) {
                const coordinationMeasure = coordinationMeasures[i];
                // Do anything with coordinationMeasure

                data.push(coordinationMeasure);
            }

            response.push(data);
        }
    });

    db.query("SELECT * FROM `entidades`;", async function (err, result) {
        if (err) throw err;

        if (result.length === 0) {
            console.log(err);
        }
        else {
            let data = [];
            let entities = JSON.parse(JSON.stringify(result));

            for (let i = 0; i < entities.length; i++) {
                const entity = entities[i];
                // do anything with entity

                data.push(entity);
                // console.log(entity);
            }

            response.push(data);
        }
    });

    return response;
}

function getRealTimeEntites(ws, id) {

    let coordinationMeasures;
    let entities;

    db.query("SELECT * FROM `medidas_coordenacao` WHERE `proprietario` = " + id + ";", function (err, result) {
        if (err) throw err;

        coordinationMeasures = JSON.parse(JSON.stringify(result));

        for (let i = 0; i < coordinationMeasures.length; i++) {

            const coordinationMeasure = coordinationMeasures[i];

            // Do anything with coordinationMeasure
        }
    });

    db.query("SELECT * FROM `entidades` WHERE `proprietario` = " + id + ";", function (err, result) {
        if (err) throw err;

        entities = JSON.parse(JSON.stringify(result));

        for (let i = 0; i < entities.length; i++) {
            const entity = entities[i];

            if (entity["tipo"] == 4) {
                let a = simulators.length

                for (let j = 0; j < simulators.length; j++) {
                    const simulator = simulators[j];

                    if (entity["id"] == simulator.getID()) {

                        // let q = "UPDATE `entidades` SET `latitude` = " +  simulator.getCoordinates()[0] + ", `longitude` = " + simulator.getCoordinates()[1] + ", `altitude` = " + simulator.getCoordinates()[2] + ", `azimute` = " + simulator.getCoordinates()[3] + " WHERE `id` = " + simulator.getID();
                        // saveQuery(q);

                        entity["latitude"] = simulator.getCoordinates()[0];
                        entity["longitude"] = simulator.getCoordinates()[1];
                        entity["altitude"] = simulator.getCoordinates()[2];
                        entity["azimute"] = simulator.getCoordinates()[3];
                        entity["updated_at"] = new Date().toISOString();

                        const msg = simulator.getCoordinates()[0] + ", " + simulator.getCoordinates()[1] + ", " + simulator.getCoordinates()[2] + ", " + simulator.getCoordinates()[3] + '\n';

                        // console.log(msg);
                    }

                }
            }
        }

        // Coloquei o WS Send aqui até resolver o problema de tempo de execuçao que não retornava os valores contidos no banco a tempo de enviar a resposta pelo soquete.
        // Para resolver será necessário certificar-se de só enviar pelo soquete quando os objetos estiverem realmente na variável ENTITIES e COORDINATIONMEASURES
        ws.send(JSON.stringify({ 'entities': entities, 'coordinationMeasures': coordinationMeasures }));

        // console.log("Sending Info in " + Date.now());
    });


    // }, 2000);
}

function getAllRealTimeEntites(ws) {

    console.log(ws);

    let users = [];

    db.query("SELECT * FROM `usuarios` WHERE `perfil` = 2;", function (err, result) {

        usersList = JSON.parse(JSON.stringify(result));

        let user = {
            id: '',
            nome: '',
            coordinationMeasures: [],
            entities: [],
        };

        for (let u = 0; u < usersList.length; u++) {
            user.id = usersList[u].id;
            user.nome = usersList[u].nome;

            db.query("SELECT * FROM `medidas_coordenacao` WHERE `proprietario` = " + user.id + ";", function (err, result) {
                if (err) throw err;

                user.coordinationMeasures.push(JSON.parse(JSON.stringify(result)));
            });

            db.query("SELECT * FROM `entidades` WHERE `proprietario` = " + user.id + ";", function (err, result) {
                if (err) throw err;

                user.entities.push(JSON.parse(JSON.stringify(result)));
            });

            console.log(user);

            users.push(user);
        }
    });

    // console.log(users);

    ws.send(JSON.stringify({ 'users': users }));
}

function saveEntities() {
    let entities;

    setInterval(() => {
        http.get(url, (res) => {
            let body = "";
            res.on("data", (chunk) => {
                body += chunk;
            });
            res.on("end", () => {
                try {

                    let json = JSON.parse(body);


                    db.query("SELECT * FROM `entidades`;", function (err, result) {
                        if (err) throw err;

                        if (result.length === 0) {
                            console.log('Vazio');
                        }
                        else {
                            entities = JSON.parse(JSON.stringify(result));

                            for (let i = 0; i < entities.length; i++) {
                                const entity = entities[i];

                                if (true) {
                                    for (let j = 0; j < json.length; j++) {

                                        const sentinela = json[j];

                                        if (entity["id"] == sentinela['id'] && sentinela['azimute'] != 'velocidade') {

                                            let q = "UPDATE `entidades` SET `latitude` = " + sentinela['latitude'] + ", `longitude` = " + sentinela['longitude'] + ", `altitude` = " + sentinela['altitude'] + ", `azimute` = " + sentinela['azimute'] + ", `updated_at` = '" + sentinela['updated_at'] + "' WHERE `id` = " + sentinela['id'];

                                            saveQuery(q);
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error(`🔴 ${error}`);
                };
            });
        }).on("error", (error) => {
            // console.error(`🔴 ${error.message}`);
        });
    }, 5000);
}

function saveQuery(query) {
    try {
        db.query(query, function (err, result) {
            if (err) throw err;

            if (result.length === 0) {
                console.log('Query não retornou com sucesso');
            }
            else {
                // console.log("Feito!")
            }
        });
    } catch (error) {
        console.log(`🔴 ${error}`);
    }
}

function accessLog(log, ip, coords) {
    // 1 - Novo cliente conectado
    // 2 - Cliente desconectado

    const timeStamp = new Date().toISOString().slice(0, 19).replace('T', ' ')

    let q = (info) => {
        return "INSERT INTO `log`(`ip`, `info`, `coordenadas`,  `created_at`) VALUES ('" + ip + "', '" + info + "', '" + coords + "', '" + timeStamp + "');";
    }

    switch (log) {
        case 1:
            saveQuery(q("Cliente conectado"));
            break;

        case 2:
            saveQuery(q("Cliente desconectado"));
            break;

        default:
            break;
    }
}

// Database connection
function connectDatabase() {
    db.connect(function (err) {
        if (err) throw err;
        console.log("📙 Banco de dados conectado. Thread ID: " + db.threadId);
    });
}
