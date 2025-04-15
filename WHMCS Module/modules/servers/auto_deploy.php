<?php

function auto_deploy_CreateAccount(array $params) {
    $clientId = $params['serviceid'];
    $domain = $params['domain'];

    if (empty($domain)) {
        return ['error' => 'O domínio não foi preenchido.'];
    }

    $url = 'http://localhost:3000/deploy';
    $data = [
        'clientId' => $clientId,
        'domain' => $domain
    ];

    $response = call_backend($url, $data);

    if ($response['success']) {
        // opcional: salvar no campo personalizado o link
        return 'success';
    } else {
        return 'Erro: ' . $response['error'];
    }
}

function auto_deploy_TerminateAccount(array $params) {
    $clientId = $params['serviceid'];
    $domain = $params['domain'];

    if (!$domain) {
        return ['error' => 'Domínio não informado para remoção'];
    }

    $url = 'http://localhost:3000/terminate';
    $data = [
        'clientId' => $clientId,
        'domain' => $domain
    ];

    $response = call_backend($url, $data);

    if (!empty($response['error'])) {
        return ['error' => 'Erro ao remover container: ' . (is_string($response['error']) ? $response['error'] : json_encode($response['error']))];
    }

    return 'success';
}

function call_backend($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) return ['success' => false, 'error' => $error];

    $decoded = json_decode($result, true);
    return $decoded ?? ['success' => false, 'error' => 'Resposta inválida'];
}
