<?php
require_once('includes/config.php');


// list all domains
$statement = $pdo->prepare('SELECT * FROM domains');
$statement->execute();

$domains = $statement->fetchAll(PDO::FETCH_ASSOC);

// list all aliases
$statement = $pdo->prepare('SELECT id, email, destination FROM virtuals ORDER BY id DESC');
$statement->execute();

$aliases = $statement->fetchAll(PDO::FETCH_ASSOC);

// list all aliases
$statement = $pdo->prepare("SELECT id, email, '...' || right(password, 5) as password FROM credentials ORDER BY id DESC");
$statement->execute();

$credentials = $statement->fetchAll(PDO::FETCH_ASSOC);

include($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php');
?>


<h1>You've got mail</h1>

<?php
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalidDomain':
            echo '<p class="error">Le domaine n\'est pas valide. Est-il configuré?</p>';
            break;

        default:
            echo "<p class=\"error\">Erreur: " . $_GET['error'] . "</p>";
            break;
    }
}
?>



<details id="alias-table">
    <summary>Tableau des alias</summary>
    <form method="post" action="/create.php">
        <input type="hidden" name="type" value="alias" />
        <label for="email">Email</label>
        <input type="text" name="email" id="email" pattern="([a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$)|(vmail)" />
        <label for="destination">Destination</label>
        <input type="text" name="destination" id="destination" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
        <input type="submit" value="Ajouter" />
    </form>
    <br />
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Destination</th>
            </tr>

        </thead>
        <tbody>
            <?php foreach ($aliases as $row): ?>
                <!--  array(3) { ["id"]=> int(1) ["email"]=> string(18) "luclu7@openbsd.lan" ["destination"]=> string(5) "vmail" } -->
                <tr>
                    <td>
                        <?= $row['email'] ?>
                    </td>
                    <td>
                        <?= $row['destination'] ?>
                    </td>
                    <td>
                        <a href="changeAlias.php?id=<?= $row['id'] ?>">
                            Modifier
                        </a>
                        <a href="/delete.php?type=alias&id=<?= $row['id'] ?>"
                            onclick="return  confirm('Êtes-vous sûr⋅e de vouloir supprimer la redirection de <?= $row['email'] ?> à <?= $row['destination'] ?> ?')">Supprimer</a>
                    </td>
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>
</details>

<details id="credentials-table">
    <summary>Tableau des comptes</summary>
    <form method="post" action="/create.php">
        <input type="hidden" name="type" value="credential" />
        <label for="email">Email</label>
        <input type="text" name="email" id="email" pattern="([a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$)|(vmail)" />
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" />
        <input type="submit" value="Ajouter" />
    </form>
    <br />
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Password</th>
            </tr>
        <tbody>
            <?php foreach ($credentials as $row): ?>
                <tr>
                    <td>
                        <?= $row['email'] ?>
                    </td>
                    <td>
                        <?= $row['password'] ?>
                    </td>
                    <td>
                        <a href="changePassword.php?id=<?= $row['id'] ?>">
                            Mot de passe
                        </a>
                        <a href="/delete.php?type=credential&id=<?= $row['id'] ?>"
                            onclick="return  confirm('Êtes-vous sûr⋅e de vouloir supprimer la le compte <?= $row['email'] ?> ?')">Supprimer</a>
                    </td>
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>
</details>
<details id="domains-table">
    <summary>Tableau des domaines</summary>
    <form method="post" action="/create.php">
        <input type="hidden" name="type" value="domain" />
        <label for="domain">Domaine</label>
        <input type="text" name="domain" id="domain" />
        <input type="submit" value="Ajouter" />
    </form>
    <br />
    <table>
        <thead>
            <tr>
                <th>Domain</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($domains as $row): ?>
                <tr>
                    <td>
                        <?= $row['domain'] ?>
                    </td>
                    <td>
                        <a href="/delete.php?type=domain&id=<?= $row['id'] ?>"
                            onclick="return  confirm('Êtes-vous sûr⋅e de vouloir supprimer le domaine <?= $row['domain'] ?> ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</details>

<script>
    // remove error code from URL
    window.history.replaceState({}, document.title, "/");

    // store details in localStorage
    const aliasTable = document.getElementById('alias-table');
    const credentialsTable = document.getElementById('credentials-table');
    const domainsTable = document.getElementById('domains-table');
    const details = [aliasTable, credentialsTable, domainsTable];

    details.forEach(detail => {
        detail.addEventListener('toggle', () => {
            localStorage.setItem(detail.id, detail.open);
        });
    });

    details.forEach(detail => {
        if (localStorage.getItem(detail.id) === 'true') {
            detail.open = true;
        }
    });
</script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/foot.php'); ?>