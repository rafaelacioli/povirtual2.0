var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io')(server);
var mysql = require('mysql');

/*
* CONFIGURAÇÃO DE CONEXÃO COM BANCO DE DADOS
*/
var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "2rgh27723",
  database: "pov"
});

con.connect(function(err) {
  if (err) throw err;
  console.log("Banco de dados conectado");
});

connections = [];

server.listen(process.env.PORT || 3000);
console.log('Servidor ativo');

//Conexão
io.sockets.on('connection', function(socket) {
	
    console.log("Usuario conectado: " + socket.id)

	// var login = false
	var cad = {};

	//Disconexão
	socket.on('disconnect', function(data) {
		// console.log(socket);
		connections.splice(connections.indexOf(socket), 1);
		console.log('Desconectado\n%s sockets estão conectados', connections.length);
		console.log(socket.id+" deletado!")
	});
	
	//Cadastro
	socket.on('validacaoup', function(data) {
		
		var resultvalidacao = [];

		const crypto = require('crypto');
		let senha = crypto.createHash('md5').update(data[2]).digest("hex");
		
	
		console.log(data[0]);
		
		//Verifica senha
		console.log('Validando Dispositivo');

		con.query("SELECT `id`, `nome` FROM `usuarios` WHERE `idtmil` = '"+data[1]+"' AND `senha` = '"+senha+"'", function (err, result) {

			if (err) throw err;

			if (result.length === 0){
				io.sockets.emit('validacaodown', resultvalidacao);
				socket.disconnect(0);
			}else{

				cad['subordinacao'] = result[0]['id'];
				cad['usuario'] = result[0]['nome'];
				resultvalidacao.push(result[0]['nome']);

				con.query("SELECT `nome` FROM `dispositivos` WHERE `id` = '"+data[0]+"'", function (err, result) {
					
					if (err) throw err;

					if (result.length === 0){
						io.sockets.emit('validacaodown', resultvalidacao);
						socket.disconnect(0);
					}else{
						console.log(result[0]['nome']);
						
						cad['dispositivo'] = result[0]['nome'];
						resultvalidacao.push(result[0]['nome']);
		
						console.log(resultvalidacao);
						io.sockets.emit('validacaodown', resultvalidacao);
					}
				});
			}
		});
	});
	
	//upLocation
	socket.on('upLocation', function(data) {

		console.log('UPLOCATION');
		console.log(data);

		//Atualiza o registro
		console.log('Atualizando registro')
		
		con.query("UPDATE `mapa` SET dispositivo = '"+cad['dispositivo']+"', ip = '"+data[1]+"', usuario = '"+cad['usuario']+"', subordinacao = '"+cad['subordinacao']+"', latitude = '"+data[4]+"', longitude = '"+data[5]+"', conf_h = '"+data[6]+"', altitude = '"+data[7]+"', conf_v = '"+data[8]+"', velocidade = '"+data[9]+"', curso = '"+data[10]+"', azimute = '"+data[11]+"', inclinacao = '"+data[12]+"', aceleracao_brusca = '"+data[13]+"', desaceleracao_brusca = '"+data[14]+"', panico = '"+data[15]+"', num_satelites = '"+data[16]+"', nivel_bat = '"+data[17]+"' WHERE id = '"+data[0]+"'", function (err, result) {
			if (err) throw err; });

		con.query("INSERT INTO `"+data[0]+"` (`id`, `ip`, `usuario`, `nome`, `latitude`, `longitude`, `conf_h`, `altitude`, `conf_v`, `velocidade`, `curso`, `azimute`, `inclinacao`, `aceleracao_brusca`, `desaceleracao_brusca`, `panico`, `num_satelites`, `nivel_bat`) VALUES ('"+data[0]+"','"+data[1]+"','"+data[2]+"','"+data[3]+"','"+data[4]+"','"+data[5]+"','"+data[6]+"','"+data[7]+"','"+data[8]+"','"+data[9]+"','"+data[10]+"','"+data[11]+"','"+data[12]+"','"+data[13]+"','"+data[14]+"','"+data[15]+"','"+data[16]+"','"+data[17]+"')", function (err, result) {
			if (err) throw err; });

	});
});