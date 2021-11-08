var express = require('express');
var app = express();
const { execSync } = require('child_process');

app.get('/', function(req, res){
  res.send('name: ' + req.query.name);
  stdout=execSync('python py_backend.py '+req.query.name).toString();
  console.log(stdout);
});

app.listen(8080);

