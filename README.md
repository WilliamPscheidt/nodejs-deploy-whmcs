
# Deploy Automático de Aplicações Node.js via WHMCS + Docker + NGINX

Este projeto integra o sistema de billing WHMCS com uma API backend em Node.js para realizar o **deploy automatizado de aplicações Node.js a partir de repositórios GitHub** em containers Docker. O sistema configura automaticamente o NGINX, redireciona o domínio/subdomínio informado pelo cliente e exibe **logs em tempo real via WebSocket**.

---

## Tecnologias Utilizadas

- Node.js
- Docker
- NGINX
- WHMCS
- WebSocket (`ws`)
- Simple-Git
- Express.js
- fs-extra

---

## ⚙️ Funcionalidades

- Deploy automático de apps Node.js a partir de repositórios GitHub
- Atribuição de domínio ou subdomínio ao container
- Geração automática de config NGINX + reload
- Suporte a autenticação via token do GitHub
- Definição da versão do Node.js utilizada
- Limpeza completa de containers, arquivos e config nginx no cancelamento
- Visualização de logs em tempo real via WebSocket no painel do WHMCS

---

## Fluxo de Funcionamento

1. O cliente faz um pedido via WHMCS e preenche:
   - Repositório GitHub
   - Token de acesso (se necessário)
   - Domínio ou subdomínio desejado
   - Versão do Node.js

2. O WHMCS executa o **hook personalizado**, que envia os dados para o backend via API REST.

3. O backend:
   - Clona o repositório
   - Cria um container Docker com a versão do Node.js especificada
   - Realiza o `npm install` e `npm start`
   - Gera a config NGINX e a aplica
   - Retorna o link de acesso ao app

4. O cliente acessa o app via domínio e visualiza logs diretamente pelo painel do WHMCS.

---

## Instalação

### Backend (Node.js)

```bash
git clone https://github.com/WilliamPscheidt/nodejs-deploy-whmcs/
cd whmcs-nodejs-deployer
npm install
node index.js
```

> **Porta padrão:** `4000`  
> **WebSocket:** roda na mesma porta

--- 

## Endpoints disponíveis

| Método | Rota        | Descrição                     |
|--------|-------------|-------------------------------|
| POST   | /deploy     | Cria e inicia container       |
| POST   | /terminate  | Encerra e remove container    |
| POST   | /start      | Inicia o container            |
| POST   | /stop       | Para o container              |
| POST   | /restart    | Reinicia o container          |
| WS     | [WebSocket] | Logs em tempo real do app     |

---

## Exemplo de Payload `/deploy`

```json
{
  "orderId": 19,
  "domain": "node19.seudominio.com.br",
  "githubRepo": "https://github.com/usuario/repo",
  "githubToken": "ghp_...",
  "nodeVersion": "20"
}
```

---

## Requisitos

- Docker instalado
- NGINX instalado e configurado para ler arquivos `.conf` em `/etc/nginx/conf.d/`
- Porta `4000` liberada
- WHMCS com módulo personalizado e hook ativado

---


---

## 📝 Licença

Este projeto está licenciado sob a MIT License.
