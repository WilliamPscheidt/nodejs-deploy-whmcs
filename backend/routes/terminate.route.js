const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
    res.send("Funcionando /terminate")
})

module.exports = router