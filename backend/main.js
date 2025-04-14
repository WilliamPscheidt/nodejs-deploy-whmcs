const express = require('express');
const deployRouter = require('./routes/deploy.route')
const terminateRouter = require('./routes/terminate.route')

const router = express();
const port = 3000;

router.use(express.json());

router.use('/deploy', deployRouter);
router.use('/terminate', terminateRouter);

router.listen(port, () => {
    console.log(`WHMCS Node.JS deploy Back-End running on ${port}`)
})
