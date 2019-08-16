'use strict';

const express = require('express');
const { fork } = require('child_process');
const exec = require('child_process').exec;

const PORT = process.env.APP_PORT;
const HOST = '0.0.0.0';

const app = express();

app.post('/', (req, res) => {
    fork('/src/index.js', [
        '--merchantCode', req.query.variables.MERCHANT_CODE,
        '--referenceId', req.query.variables.REFERENCE_ID,
        '--key', req.query.variables.KEY,
    ]);
    res.status(200).send();
});

app.listen(PORT, HOST);
console.log(`Running on http://${HOST}:${PORT}`);
