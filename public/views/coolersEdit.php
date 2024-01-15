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
    <title>Edycja chłodzeń CPU</title>

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
    <?php
                       if(isset($messages)){
                           foreach ($messages as $message) {
                               echo $message;
                           }
                       }
                       ?>
        <div>
            <img id="addButton" src="../../public/img/dodaj.png" alt="dodaj">
        </div>
        <div id="formContainer" style="display: none;">
            <!-- Tutaj dodaj formularz do dodawania nowego chłodzenia CPU -->
            <form action="addCooler" method="post" class="form" enctype="multipart/form-data">
                <div class="container">
                    <!-- Dodaj pola formularza dla nowego chłodzenia CPU -->
                    <!-- Pamiętaj, aby odpowiednio dostosować pola do Twoich potrzeb -->
                    <label for="manufacture">Producent:</label>
                    <input type="text" id="manufacture" name="manufacture" required>

                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model" required>
                    <!-- Podgląd zdjęcia -->
                    <img class="preview" src="../../public/img/default-preview-image.png" alt="Image preview">
                    <br>

                    <!-- Dodane pole do zmiany zdjęcia -->
                    <label for="photo">Zdjęcie:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">

                    <!-- Dodane pole do zmiany ceny -->
                    <label for="price">Cena:</label>
                    <input type="number" id="price" name="price" required>

                    <label for="type">Rodzaj:</label>
                    <input type="text" id="type" name="type" required>

                    <label for="fan_count">Liczba wentylatorów:</label>
                    <input type="number" step="1" id="fan_count" name="fan_count" required>

                    <label for="fan_size">Rozmiar wentylatorów:</label>
                    <input type="number" step="1" id="fan_size" name="fan_size" required>

                    <label for="backlight">Podświetlenie:</label>
                    <input type="checkbox" id="backlight" name="backlight">

                    <label for="material">Materiał:</label>
                    <input type="text" id="material" name="material" required>

                    <label for="radiator_size">Rozmiar radiatora:</label>
                    <input type="text" id="radiator_size" name="radiator_size" required>

                    <label for="compatibility">Kompatybilność:</label>
                    <input type="text" id="compatibility" name="compatibility" required>

                    <button type="submit">Dodaj</button>
                </div>
            </form>
        </div>
        <div>
            <?php foreach ($coolers as $cooler): ?>
                <form action="updateCooler" method="post" class="form" enctype="multipart/form-data">
                    <div class="container">
                        <h3><?= $cooler->getManufacture();?> <?= $cooler->getModel(); ?></h3>
                        <input type="number" id="id" name="id" hidden disabled value="<?= $cooler->getId(); ?>">
                        <br>
                        <label for="manufacture">Producent:</label>
                        <input type="text" id="manufacture" name="manufacture" value="<?= $cooler->getManufacture(); ?>">

                        <label for="model">Model:</label>
                        <input type="text" id="model" name="model" value="<?= $cooler->getModel(); ?>">
                        <!-- Podgląd zdjęcia -->
                        <img class="preview" src="../../public/img/<?= $cooler->getPhoto(); ?>?t=<?= time(); ?>" alt="Image preview">
                        <br>

                        <!-- Dodane pole do zmiany zdjęcia -->
                        <label for="photo">Zdjęcie:</label>
                        <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">

                        <!-- Dodane pole do zmiany ceny -->
                        <label for="price">Cena:</label>
                        <input type="number" id="price" name="price" value="<?= $cooler->getPrice(); ?>">

                        <label for="type">Rodzaj:</label>
                        <input type="text" id="type" name="type" value="<?= $cooler->getType(); ?>">

                        <label for="fan_count">Liczba wentylatorów:</label>
                        <input type="number" id="fan_count" name="fan_count" value="<?= $cooler->getFanCount(); ?>">

                        <label for="fan_size">Rozmiar wentylatorów:</label>
                        <input type="number" id="fan_size" name="fan_size" value="<?= $cooler->getFanSize(); ?>">

                        <label for="backlight">Podświetlenie:</label>
                        <input type="checkbox" id="backlight" name="backlight" <?= $cooler->getBacklight() ? 'checked' : ''; ?>>

                        <label for="material">Materiał:</label>
                        <input type="text" id="material" name="material" value="<?= $cooler->getMaterial(); ?>">

                        <label for="radiator_size">Rozmiar radiatora:</label>
                        <input type="text" id="radiator_size" name="radiator_size" value="<?= $cooler->getRadiatorSize(); ?>">

                        <label for="compatibility">Kompatybilność:</label>
                        <input type="text" id="compatibility" name="compatibility" value="<?= $cooler->getCompatibility(); ?>">

                        <button type="submit">Edytuj</button>
                        <a href="deleteCooler?id=<?= $cooler->getId(); ?>"><button type="button">Usuń</button></a>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </main>
</body>

</html>
