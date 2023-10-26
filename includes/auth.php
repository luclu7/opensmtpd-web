<?php
// require http basic auth

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="OpenSMTPD WebUI"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Vous devez vous authentifier pour accéder à cette page';
    exit();
} else {
    $statement = $pdo->prepare('SELECT * FROM credentials WHERE email = :email');
    $statement->execute([
        ':email' => $_SERVER['PHP_AUTH_USER']
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($_SERVER['PHP_AUTH_PW'], $user['password'])) {
            // password is correct
        } else {
            header('WWW-Authenticate: Basic realm="OpenSMTPD WebUI"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Vous devez vous authentifier pour accéder à cette page';
            exit();
        }
    } else {
        header('WWW-Authenticate: Basic realm="OpenSMTPD WebUI"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Vous devez vous authentifier pour accéder à cette page';
        exit();
    }
}