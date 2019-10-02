'use strict';

const express = require('express');
const {fork} = require('child_process');
const exec = require('child_process').exec;

const PORT = process.env.APP_PORT;
const HOST = '0.0.0.0';

const app = express();


app.post('/', (req, res) => {
    process.env.MERCHANT_CODE = req.query.variables.MERCHANT_CODE;
    process.env.REFERENCE_ID = req.query.variables.REFERENCE_ID;
    process.env.KEY = req.query.variables.KEY;

    fork('/src/index.js', []);
    res.status(200).send();
});

app.listen(PORT, HOST);
console.log(`Running on http://${HOST}:${PORT}`);
