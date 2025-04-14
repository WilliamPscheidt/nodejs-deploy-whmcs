const express = require('express');
const terminateController = require('../controllers/terminate.controller')
const router = express.Router();

router.post('/', terminateController.terminate)

module.exports = router