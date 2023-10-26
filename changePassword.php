<?php
include_once('includes/config.php');


if (isset($_POST['id']) && isset($_POST['password'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // calculate blowfish hash of password with a cost of 9
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 9]);

    // change hash to be compatible with dovecot
    $hash = str_replace('$2y$09$', '$2b$09$', $hash);

    // update password
    $statement = $pdo->prepare('UPDATE credentials SET password = :password WHERE id = :id');
    $statement->execute([
        'id' => $id,
        'password' => $hash
    ]);

    header('Location: /');
}

if (isset($_GET['id'])) {

    $statement = $pdo->prepare('SELECT email, id FROM credentials WHERE id = :id');
    $statement->execute([
        ':id' => $_GET['id']
    ]);

    $credential = $statement->fetch(PDO::FETCH_ASSOC);

    if ($credential) {
        $email = $credential['email'];
        $id = $credential['id'];
    } else {
        header('Location: /?error=invalidID');
        exit();
    }
} else {
    header('Location: /?error=noIDGiven');
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php');
?>
<h1>Modifier le mot de passe de
    <?= $email ?>
</h1>
<form action="changePassword.php?id=<?= $_GET['id'] ?>" method="post">
    <input type="hidden" name="id" value="<?= $id ?>" />
    <label for="password">Nouveau mot de passe</label>
    <input type="password" name="password" id="password" />
    <input type="submit" value="Modifier" />
    <button onclick="history.back();">Retour</button>
</form>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/foot.php'); ?>