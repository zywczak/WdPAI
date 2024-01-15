<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ta strona to będzie coś wspaniałego">
    <meta name="keywords" content="strona, wspaniała, niczym">
    <meta name="author" content="Piotr Żywczak">
    <link rel="icon" type="image/x-icon" href="../../public/img/logo.png">
    <link rel="stylesheet" type="text/css" href="../../public/css/style140.css">
    <?php if ($_SESSION['user_type'] != 'admin') : ?>
        <link rel="stylesheet" type="text/css" href="../../public/css/style100.css">
    <?php endif; ?>
    <script src="../../public/js/script1.js"></script>
    <title>Edycja RAMu</title>

    <script src="../../public/js/viewImage.js"></script>
    <script src="../../public/js/addProduct.js"></script>
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
    <div>
            <img id="addButton" src="../../public/img/dodaj.png" alt="dodaj">
        </div>
        <div id="formContainer" style="display: none;">
            <!-- Tutaj dodaj formularz do dodawania nowego chłodzenia CPU -->
            <form action="addRam" method="post" class="form" enctype="multipart/form-data">
                <div class="container">
                    <!-- Dodaj pola formularza dla nowego chłodzenia CPU -->
                    <!-- Pamiętaj, aby odpowiednio dostosować pola do Twoich potrzeb -->
                    <label for="manufacture">Producent:</label>
                    <input type="text" id="manufacture" name="manufacture">

                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model">
                    <!-- Podgląd zdjęcia -->
                    <img class="preview" src="../../public/img/default-preview-image.png" alt="Image preview">
                    <br>

                    <!-- Dodane pole do zmiany zdjęcia -->
                    <label for="photo">Zdjęcie:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">

                    <!-- Dodane pole do zmiany ceny -->
                    <label for="price">Cena:</label>
                    <input type="number" id="price" name="price">

                    <label for="speed">Taktowanie:</label>
                <input type="number" name="speed" >

                <label for="capacity">Pojemność:</label>
                <input type="text" name="capacity">

                <label for="voltage">Napięcie:</label>
                <input type="number" name="voltage" >

                <label for="module_count">Liczba modułów:</label>
                <input type="number" name="module_count" >

                <label for="backlight">Podświetlenie:</label>
                <input type="checkbox" name="backlight" >

                <label for="cooling">Radiator:</label>
                <input type="checkbox" name="cooling" >

                    <button type="submit">Dodaj</button>
                </div>
            </form>
        </div>
    <div>
    <?php foreach ($rams as $ram): ?>
        <form action="editRam" method="post" class="form" enctype="multipart/form-data">
            <div class="container">
                <h3><?= $ram->getManufacture(); ?> <?= $ram->getModel(); ?></h3>
                <br>
                <!-- Include RAM form fields based on your requirements -->
                <!-- Example: -->
                <label for="manufacture">Producent:</label>
                <input type="text" name="manufacture" value="<?= $ram->getManufacture(); ?>">

                <label for="model">Model:</label>
                <input type="text" name="model" value="<?= $ram->getModel(); ?>">
                
                <img class="preview" src="../../public/img/<?= $ram->getPhoto(); ?>?t=<?= time(); ?>" alt="Image preview">
                <br>

                <!-- Dodane pole do zmiany zdjęcia -->
                <label for="photo">Zdjęcie:</label>
                <input type="file" name="photo" accept="image/*" onchange="previewImage(this)">
                
                <!-- Dodane pole do zmiany ceny -->
                <label for="price">Cena:</label>
                <input type="number" name="price" value="<?= $ram->getPrice(); ?>">

                <label for="speed">Taktowanie:</label>
                <input type="number" name="speed" value="<?= $ram->getSpeed(); ?>">

                <label for="capacity">Pojemność:</label>
                <input type="text" name="capacity" value="<?= $ram->getCapacity(); ?>">

                <label for="voltage">Napięcie:</label>
                <input type="number" name="voltage" value="<?= $ram->getVoltage(); ?>">

                <label for="module_count">Liczba modułów:</label>
                <input type="number" name="module_count" value="<?= $ram->getModuleCount(); ?>">

                <label for="backlight">Podświetlenie:</label>
                <input type="checkbox" name="backlight" <?= $ram->getBacklight() ? 'checked' : ''; ?>>

                <label for="cooling">Radiator:</label>
                <input type="checkbox" name="cooling" <?= $ram->getCooling() ? 'checked' : ''; ?>>

                <button type="submit">Edytuj</button>
                <a href="deleteRam?id=<?= $ram->getId(); ?>"><button type="submit">Usuń</button></a>

            </div>
        </form>
        <?php endforeach; ?>
    </div>
    </main>
    </body>
</html>
