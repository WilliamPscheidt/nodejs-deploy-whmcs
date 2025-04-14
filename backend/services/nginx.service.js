const createNginxConfiguration = (domain, port) => {
    return `
    server {
        listen 80;
        server_name ${domain};
    
        location / {
            proxy_pass http://127.0.0.1:${port};
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
        }
    }`;
}

const reloadNginxConfiguration = () => {
    exec('nginx -s reload', (error, stdout, stderr) => {
        if (error) console.error(`Erro ao recarregar NGINX: ${stderr}`);
        else console.log('NGINX recarregado com sucesso.');
    });
}