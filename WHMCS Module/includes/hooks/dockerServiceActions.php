<?php

use WHMCS\Database\Capsule;

add_hook('ClientAreaProductDetailsOutput', 1, function($vars) {
    $serviceId = $vars['service']->id ?? null;
    $backendUrl = 'http://localhost:4000'; // Hardcoded (Temporário)

    $actions = ['start' => 'Iniciar', 'restart' => 'Reiniciar', 'stop' => 'Parar', 'logs' => 'Ver Logs'];
    $buttons = '';

    foreach ($actions as $action => $label) {
        $buttons .= <<<HTML
            <form method="post" action="" style="display:inline-block;margin-right:10px;">
                <input type="hidden" name="docker_action" value="{$action}">
                <input type="submit" class="btn btn-default" value="{$label}">
            </form>
        HTML;
    }

    $logs = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['docker_action'], array_keys($actions))) {
        $action = $_POST['docker_action'];

        $payload = json_encode(['serviceId' => $serviceId]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$backendUrl/{$action}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            $logs .= "<pre>Erro CURL: $error</pre>";
        }

        curl_close($ch);

        if ($action === 'logs') {
            $data = json_decode($response, true);
            $logOutput = htmlspecialchars($data['logs'] ?? 'Sem logs');
            $logs = "<pre style='background:#000;color:#0f0;padding:10px;height:300px;overflow:auto;'>$logOutput</pre>";
        }
    }

    return $buttons . $logs;
});
