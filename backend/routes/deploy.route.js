const express = require('express');
const deployController = require('../controllers/deploy.controller')

const router = express.Router();

router.post('/', deployController.deploy)

module.exports = router