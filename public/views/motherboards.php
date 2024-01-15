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
    <title>Płyty główne</title>
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
            <?php foreach ($motherboards as $motherboard): ?>
                <div class="motherboard-container">
                    <h3><?= $motherboard->getManufacture();?> <?= $motherboard->getModel(); ?></h3>
                    <img src="../../public/img/<?= $motherboard->getPhoto(); ?>" alt="Image not available">
                    <br>
                    <span>Chipset: <?= $motherboard->getChipset(); ?></span>
                    <span>Format: <?= $motherboard->getFormFactor(); ?></span>
                    <span>Obsługiwana pamięć: <?= $motherboard->getSupportedMemory(); ?></span>
                    <span>Gniazdo procesora: <?= $motherboard->getSocket(); ?></span>
                    <span>Architektura procesora: <?= $motherboard->getCpuArchitecture(); ?></span>
                    <span>Wewnętrzne złącza: <?= $motherboard->getInternalConnectors(); ?></span>
                    <span>Zewnętrzne złącza: <?= $motherboard->getExternalConnectors(); ?></span>
                    <span>Liczba banków pamięci: <?= $motherboard->getMemorySlots(); ?></span>
                    <span>Układ audio: <?= $motherboard->getAudioSystem(); ?></span>
                    <span class="price"><?= $motherboard->getPrice(); ?> zł</span>
                    <img src="../../public/img/do_koszyka.png" alt="do koszyka">
                </div>
            <?php endforeach; ?>
        </main>
    </body>
</html>
