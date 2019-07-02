'use strict';

const express = require('express');
const { fork } = require('child_process');
const exec = require('child_process').exec;

const PORT = process.env.APP_PORT;
const HOST = '0.0.0.0';

const app = express();

app.use(express.json());

app.post('/', (req, res) => {
    fork('/src/index.js', [
        '--merchantCode', req.body.MERCHANT_CODE,
        '--referenceId', req.body.REFERENCE_ID,
        '--key', req.body.KEY
    ]);
    res.status(200).send();
});

app.listen(PORT, HOST);
console.log(`Running on http://${HOST}:${PORT}`);