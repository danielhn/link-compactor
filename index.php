<?php
require_once __DIR__ .  '/config.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link shortener</title>
</head>
<body>
    <main>
        <h1>Link Shortener</h1>
        <form action="./shorten_link.php" method="post">
            <label for="url">Link to shorten</label>
            <input type="url" name="url" id="url" maxlength="<?= URL_MAX_LENGTH ?>" required>
            <button type="submit">Create short link</button>
        </form>
    </main>
</body>
</html>
