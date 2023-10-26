<?php
include_once('includes/config.php');

if ($_GET) {
    if (isset($_GET['type'])) {
        switch ($_GET['type']) {
            case 'alias':
                if (isset($_GET['id'])) {
                    $statement = $pdo->prepare('DELETE FROM virtuals WHERE id = :id');
                    $statement->execute([
                        ':id' => $_GET['id']
                    ]);
                }
                break;

            case 'credential':
                if (isset($_GET['id'])) {
                    $statement = $pdo->prepare('DELETE FROM credentials WHERE id = :id');
                    $statement->execute([
                        ':id' => $_GET['id']
                    ]);
                }
                break;

            case 'domain':
                if (isset($_GET['id'])) {
                    $statement = $pdo->prepare('DELETE FROM domains WHERE id = :id');
                    $statement->execute([
                        ':id' => $_GET['id']
                    ]);
                }
                break;
        }
    }
}

// redirect to index.php
header('Location: /');
