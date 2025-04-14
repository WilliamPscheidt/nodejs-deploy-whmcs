const Docker = require('dockerode');

const docker = new Docker();

const verifyDockerImage = async (imageName) => {
    const images = await docker.listImages();
    const exists = images.some(img => img.RepoTags?.includes(imageName));

    if (!exists) {
        await new Promise((resolve, reject) => {
            docker.pull(imageName, (err, stream) => {
                if (err) return reject(err);
                docker.modem.followProgress(stream, onFinished, onProgress);

                function onFinished(err, output) {
                    if (err) return reject(err);
                    resolve(output);
                }
            });
        });
    }
};

const createDockerContainer = async (imageName, containerName) => {
    const container = await docker.createContainer({
        Image: imageName,
        name: containerName,
        HostConfig: {
            Binds: [`${appPath}:/usr/src/app`],
            PortBindings: {
                '3000/tcp': [{ HostPort: port.toString() }],
            },
        },
        WorkingDir: '/usr/src/app',
        Cmd: ['sh', '-c', 'npm install && npm start'],
        ExposedPorts: { '3000/tcp': {} },
    });

    await container.start();
}

module.exports = {
    verifyDockerImage,
    createDockerContainer
}