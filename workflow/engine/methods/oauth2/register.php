<?php
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $G_PUBLISH = new Publisher();
        $tpl = array_key_exists('l', $_GET) ? 'oauth2/registration_done' : 'oauth2/register';
        $G_PUBLISH->AddContent('view', $tpl);

        G::RenderPage('publish', 'minimal');
    break;

    case 'POST':
        $data = $_POST['form'];
        $clientId = G::generateCode(32, 'ALPHA');
        $secret = G::generateUniqueID();

        $client = new OauthClients();
        $client->setClientId($clientId);
        $client->setClientSecret($secret);
        $client->setClientName($data['name']);
        $client->setClientDescription($data['description']);
        $client->setClientWebsite($data['web_site']);
        $client->setRedirectUri($data['callback_url']);
        $client->setUsrUid($_SESSION['USER_LOGGED']);

        $client->save();

        $data['clientId'] = $clientId;
        $data['secret'] = $secret;

        header('location: register?l=' . base64_encode(json_encode($data)));
    break;
}