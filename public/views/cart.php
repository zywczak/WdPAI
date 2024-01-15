<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ta strona to będzie coś wspaniałego">
    <meta name="keywords" content="strona, wspaniała, niczym">
    <meta name="author" content="Piotr Żywczak">
    <link rel="icon" type="image/x-icon" href="../../public/img/logo.png">
    <link rel="stylesheet" type="text/css" href="../../public/css/style80.css">
    <link rel="stylesheet" type="text/css" href="../../public/css/style40.css">
    <?php if ($_SESSION['user_type'] != 'admin') : ?>
        <link rel="stylesheet" type="text/css" href="../../public/css/style100.css">
    <?php endif; ?>
    <script src="../../public/js/script1.js"></script>
    <title>Koszyk</title>
</head>

<body>
    <header>
        <div>
            <img src="../../public/img/logo1.png" alt="Logo should be here">
        </div>
        <div>
            <img src="../../public/img/user.png" alt="user">
            <span id="logged_user"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] . ' ' . $_SESSION['user_surname'] : '' ?></span>
            <a href="cart"><img class="cart" src="../../public/img/koszyk.png" alt="koszyk"></a>
            <a href="logout"><img src="../../public/img/logout.png" alt="logout"></a>
        </div>
    </header>

    <nav>
    <div>
        <a href="cpus">CPU</a>
        <?php if ($_SESSION['user_type'] == 'admin') : ?>
            <a href="cpusEdit">
                <img class="cpus_edit" src="../../public/img/edit.png" alt="edit">
            </a>
        <?php endif; ?>
    </div>

    <div>
        <a href="coolers">Chłodzenie CPU</a>
        <?php if ($_SESSION['user_type'] == 'admin') : ?>
            <a href="coolersEdit">
                <img class="coolers_edit" src="../../public/img/edit.png" alt="edit">
            </a>
        <?php endif; ?>
    </div>

    <div>
        <a href="motherboards">Płyty główne</a>
        <?php if ($_SESSION['user_type'] == 'admin') : ?>
            <a href="motherboardsEdit">
                <img class="motherboards_edit" src="../../public/img/edit.png" alt="edit">
            </a>
        <?php endif; ?>
    </div>

    <div>
        <a href="rams">RAM</a>
        <?php if ($_SESSION['user_type'] == 'admin') : ?>
            <a href="ramsEdit">
                <img class="rams_edit" src="../../public/img/edit.png" alt="edit">
            </a>
        <?php endif; ?>
    </div>
</nav>


    <main>
        <h1>Koszyk</h1>

        <?php if (!empty($cartItems)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Producent</th>
                        <th>Model</th>
                        <th>Zdjęcie</th>
                        <th>Cena</th>
                        <th>Ilość</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) : ?>
                        <tr>
                            <td><?= $item->getManufacture() ?></td>
                            <td><?= $item->getModel() ?></td>
                            <td><img src="../../public/img/<?= $item->getPhoto() ?>" alt="<?= $item->getPhoto() ?>"></td>
                            <td><?= $item->getQuantity() ?></td>
                            <td><?= $item->getPrice() ?> zł</td>
                            <td>
                                <a href="remove?<?= $item->getId() ?>"><img src="../../public/img/remove.png" alt="remove"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4"></td>
                        <td><?= $this->calculateTotalValue($cartItems) ?> zł</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div>
                <form action="clear" method="post">
                    <button type="submit">Clear</button>
                </form>
                <form action="clear" method="post">
                    <button type="submit">Zamów</button>
                </form>
            </div>
        <?php else : ?>
            <h5>Twój koszyk jest pusty.</h5>
        <?php endif; ?>
    </main>

</body>

</html>
