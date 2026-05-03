<?php
$odoo_url = "https://phpstock1.odoo.com/";
$db = "phpstock1";
$username = "mohamedouarari1388@gmail.com";
$API = "cd6faf0794e90ecaee26bec072ecc7ca409ea730";

function odoo_call($url, $method, $params = [])
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'jsonrpc' => '2.0',
        'method' => 'call',
        'params' => $params,
        'id' => time()
    ]));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die('Erreur cURL: ' . curl_error($ch));
    }
    curl_close($ch);
    $data = json_decode($response, true);
    if (isset($data['error'])) {
        die('Erreur Odoo: ' . $data['error']['message']);
    }
    return $data['result'];
}

$uid = odoo_call("$odoo_url/jsonrpc", 'call', [
    'service' => 'common',
    'method' => 'login',
    'args' => [$db, $username, $API]
]);

if (!$uid) {
    die("Impossible de se connecter à Odoo ! Vérifiez vos identifiants.");
}

function odoo_execute($model, $method, $args = [], $kwargs = [])
{
    global $odoo_url, $db, $uid, $API;
    return odoo_call("$odoo_url/jsonrpc", 'call', [
        'service' => 'object',
        'method' => 'execute_kw',
        'args' => [$db, $uid, $API, $model, $method, $args, $kwargs]
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['nomProduit']) && !isset($_POST['vendre'])) {
        $nom = $_POST['nomProduit'];
        $prix = floatval($_POST['prix']);
        $quantite = floatval($_POST['quantite']);
        $categorie = trim($_POST['categorie']);

        if (!empty($nom) && $prix >= 0 && $quantite >= 0) {
            odoo_execute('product.product', 'create', [[
                'name' => $nom,
                'list_price' => $prix,
                'x_stock_demo' => $quantite,
                'type' => 'consu',
                'x_categorie_name' => $categorie
            ]]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if (isset($_POST['vendre'])) {
        $id = intval($_POST['vendre']);
        $current = odoo_execute('product.product', 'read', [[$id]], ['fields' => ['x_stock_demo']]);

        if (!empty($current)) {
            $old_stock = $current[0]['x_stock_demo'];
            if ($old_stock > 0) {
                odoo_execute('product.product', 'write', [[$id], ['x_stock_demo' => $old_stock - 1]]);
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$products = odoo_execute('product.product', 'search_read', [[]], [
    'fields' => ['id', 'name', 'list_price', 'x_stock_demo', 'x_categorie_name']
]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-commerceStock</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>e-commerce stock</h1>
    <hr>
    <h2>ajouter un produit</h2>
    <form action="" method="post">
        <label for="nomProduit">le nom du produit</label>
        <input id="nomProduit" name="nomProduit" type="text">
        <label for="categorie">la catégorie</label>
        <input id="categorie" name="categorie" type="text">
        <label for="prix">le prix</label>
        <input id="prix" name="prix" type="number">
        <label for="quantite">la quantité</label>
        <input id="quantite" name="quantite" type="number">
        <button type="submit">envoyer</button>
    </form>

    <?php
    if (isset($erreur)) {
        echo "<p style='color:red;'>{$erreur}!!!</p>";
    }
    ?>
    <hr>
    <h2>liste des produits</h2>

    <table>
        <tr>
            <th>id</th>
            <th>nom du produit</th>
            <th>catégorie</th>
            <th>prix</th>
            <th>quantite</th>
            <th>vendre</th>
        </tr>

        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['name'] ?></td>
                <td><?= !empty($p['x_categorie_name']) ? $p['x_categorie_name'] : 'N/C'; ?></td>
                <td><?= number_format($p['list_price'], 2) ?> DH</td>
                <td><?= $p['x_stock_demo'] ?></td>
                <td>
                    <form action="" method="post">
                        <button name="vendre" value="<?= $p['id'] ?>">Vendre -1</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>