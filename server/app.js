var  app = require('express')();
var http = require('http').Server(app);

var io = require('socket.io')(http);

var ioredis = require('ioredis');

var redis = new ioredis();


// redis.subscribe()

http.listen('3000' , function() {
    console.log('listening on port 3000');
});