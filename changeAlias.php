<?php
include_once('includes/config.php');


// si requête POST: on traite le formulaire
if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['destination'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $destination = $_POST['destination'];

    // update password
    $statement = $pdo->prepare('UPDATE virtuals SET email = :email, destination = :destination WHERE id = :id');
    $statement->execute([
        'id' => $id,
        'email' => $email,
        'destination' => $destination
    ]);

    header('Location: /');
}

// si GET: on affiche le formulaire
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $statement = $pdo->prepare('SELECT id, email, destination FROM virtuals WHERE id = :id');
    $statement->execute([
        ':id' => $id
    ]);

    $alias = $statement->fetch(PDO::FETCH_ASSOC);

    if ($alias) {
        $id = $alias['id'];
        $email = $alias['email'];
        $destination = $alias['destination'];
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
<h1>Modifier l'alias
    <?= $email ?> &rarr;
    <?= $destination ?>
</h1>
<form action="changeAlias.php?id=<?= $_GET['id'] ?>" method="post">
    <input type="hidden" name="id" value="<?= $id ?>" />
    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?= $email ?>" />
    <label for="destination">Nouvelle destination</label>
    <input type="text" name="destination" id="destination" value="<?= $destination ?>" />
    <input type="submit" value="Modifier" />
    <button onclick="history.back();">Retour</button>
</form>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/foot.php'); ?>