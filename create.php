<?php
include_once('includes/config.php');


// check if POST data is present
if ($_POST) {
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case 'alias':
                // check if email and destination are present
                if (isset($_POST['email']) && isset($_POST['destination'])) {
                    // insert into database
                    $statement = $pdo->prepare('INSERT INTO virtuals (email, destination) VALUES (:email, :destination)');
                    $statement->execute([
                        ':email' => $_POST['email'],
                        ':destination' => $_POST['destination']
                    ]);
                } else {
                    header('Location: /?error=invalidAlias?email=' . $_POST['email'] . '&destination=' . $_POST['destination'] . '&type=' . $_POST['type']);
                    exit();
                }
                break;


            case 'credential':
                // check if email and destination are present
                if (isset($_POST['email']) && isset($_POST['password'])) {
                    // hash password
                    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 9]);
                    // change hash to be compatible with dovecot
                    $hash = str_replace('$2y$09$', '$2b$09$', $hash);

                    // insert into database
                    $statement = $pdo->prepare('INSERT INTO credentials (email, password) VALUES (:email, :password)');
                    $statement->execute([
                        ':email' => $_POST['email'],
                        ':password' => $hash
                    ]);
                } else {
                    header('Location: /?error=invalidCredentials?email=' . $_POST['email'] . '&password=' . $_POST['hash'] . '&type=' . $_POST['type']);
                    exit();
                }

            case 'domain':
                // check if email and destination are present
                if (isset($_POST['domain'])) {
                    // insert into database
                    $statement = $pdo->prepare('INSERT INTO domains (domain) VALUES (:domain)');
                    $statement->execute([
                        ':domain' => $_POST['domain'],
                    ]);
                } else {
                    header('Location: /?error=invalidDomain');
                    exit();
                }

            default:
                break;
        }
    }
}

header('Location: /');
