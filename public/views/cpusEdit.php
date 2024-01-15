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
    <title>Edycja CPU</title>
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
            <form action="addCpu" method="post" class="form" enctype="multipart/form-data">
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
                <input type="number" name="speed">
                
                <label for="architecture">Architektura:</label>
                <input type="text" name="architecture" >
                
                <label for="supported_memory">Obsługiwana pamięć:</label>
                <input type="text" name="supported_memory">
                
                <label for="cooling">Chłodzenie:</label>
                <input type="checkbox" name="cooling" >
                
                <label for="threads">Rdzenie:</label>
                <input type="number" name="threads" >
                
                <label for="technological_process">Litografia:</label>
                <input type="text" name="technological_process">
                
                <label for="power_consumption">Pobór mocy:</label>
                <input type="number" name="power_consumption">

                    <button type="submit">Dodaj</button>
                </div>
            </form>
        </div>
    <div>

    <?php foreach ($cpus as $cpu): ?>
        <form action="editCpu" method="post" class="form" enctype="multipart/form-data">
            <div class="container">
                <h3><?= $cpu->getManufacture();?> <?= $cpu->getModel(); ?></h3>
                <br>
                <label for="manufacture">Producent:</label>
                <input type="text" name="manufacture" value="<?= $cpu->getManufacture(); ?>">

                <label for="model">Model:</label>
                <input type="text" name="model" value="<?= $cpu->getModel(); ?>">
                <!-- Podgląd zdjęcia -->
                <img class="preview" src="../../public/img/<?= $cpu->getPhoto(); ?>?t=<?= time(); ?>" alt="Image preview">
                <br>

                <!-- Dodane pole do zmiany zdjęcia -->
                <label for="photo">Zdjęcie:</label>
                <input type="file" name="photo" accept="image/*" onchange="previewImage(this)">
                
                <!-- Dodane pole do zmiany ceny -->
                <label for="price">Cena:</label>
                <input type="number" name="price" value="<?= $cpu->getPrice(); ?>">
                
                <label for="speed">Taktowanie:</label>
                <input type="number" name="speed" value="<?= $cpu->getSpeed(); ?>">
                
                <label for="architecture">Architektura:</label>
                <input type="text" name="architecture" value="<?= $cpu->getArchitecture(); ?>">
                
                <label for="supported_memory">Obsługiwana pamięć:</label>
                <input type="text" name="supported_memory" value="<?= $cpu->getSupportedMemory(); ?>">
                
                <label for="cooling">Chłodzenie:</label>
                <input type="checkbox" name="cooling" <?= $cpu->getCooling() ? 'checked' : ''; ?>>
                
                <label for="threads">Rdzenie:</label>
                <input type="number" name="threads" value="<?= $cpu->getThreads(); ?>">
                
                <label for="technological_process">Litografia:</label>
                <input type="text" name="technological_process" value="<?= $cpu->getTechnologicalProcess(); ?>">
                
                <label for="power_consumption">Pobór mocy:</label>
                <input type="number" name="power_consumption" value="<?= $cpu->getPowerConsumption(); ?>">
                
                <button type="submit">Edytuj</button>
                <a href="deleteCpu?id=<?= $cpu->getId(); ?>"><button type="submit">Usuń</button></a>
            </div>
        </form>
    <?php endforeach; ?>
    </div>
</main>


    </body>
</html>
