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
    <?php if ($_SESSION['user_type'] != 'admin') : ?>
        <link rel="stylesheet" type="text/css" href="../../public/css/style100.css">
    <?php endif; ?>
    <script src="../../public/js/script1.js"></script>
    <title>Chłodzenie CPU</title>
</head>

<body>
    <header>
        <div>
            <img src="../../public/img/logo1.png" alt="Logo should be here">
        </div>
        <div>
            <img src="../../public/img/user.png" alt="user">
            <span id="logged_user"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] . ' ' . $_SESSION['user_surname'] : '' ?></span>
            <a href="cart"><img class="cart" src="../public/img/koszyk.png" alt="koszyk"></a>
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
    <?php foreach ($coolers as $cooler): ?>
        <div class="cooler-container">
            <h3><?= $cooler->getManufacture();?> <?= $cooler->getModel(); ?></h3>
            <img src="../../public/img/<?= $cooler->getPhoto(); ?>?t=<?= time(); ?>" alt="Image preview">
            <br>
            <span>Rodzaj: <?= $cooler->getType(); ?></span>
            <span>Liczba wentylatorów: <?= $cooler->getFanCount(); ?></span>
            <span>Rozmiar wentylatorów: <?= $cooler->getFanSize(); ?> mm</span>
            <span>Podświetlenie: <?= $cooler->getBacklight() ? 'Tak' : 'Nie'; ?></span>
            <span>Materiał: <?= $cooler->getMaterial(); ?></span>
            <span>Rozmiar radiatora: <?= $cooler->getRadiatorSize(); ?> mm</span>
            <span>Kompatybilność: <?= $cooler->getCompatibility(); ?></span>
            <span class="price"><?= $cooler->getPrice(); ?> zł</span>
            <img src="../../public/img/do_koszyka.png" alt="do koszyka">
        </div>
    <?php endforeach; ?>
</main>

</body>

</html>
