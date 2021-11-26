var escapeshellarg = require('escapeshellarg');
var express = require('express');
var app = express();
const { execSync } = require('child_process');

app.get('/', function(req, res){
  req.query.name=escapeshellarg(req.query.name);
  res.send('name: ' + req.query.name);
  stdout=execSync('python py_backend.py '+req.query.name).toString();
  console.log(stdout);
});

app.listen(8000);

