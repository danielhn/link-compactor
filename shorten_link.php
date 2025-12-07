<?php

require_once __DIR__ .  '/config.php';
require_once __DIR__ . '/env.php';

$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);

if ($url && strlen($url) <= URL_MAX_LENGTH) {
    $shortLinkId = generateShortLinkId();
    $url = htmlspecialchars($url);

    $db = new PDO(SQLITE_DATABASE_PATH);
    $sql = "INSERT INTO linkshortener (id, url) VALUES (:id, :url)";

    $db->prepare($sql)->execute([$shortLinkId, $url]);

    $shortLink = generateShortLink($shortLinkId);
}

function generateShortLinkId(): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < SHORT_LINK_ID_LENGTH; $i++) {
        $randomChar = random_int(0, strlen($chars));
        $randomString .= $chars[$randomChar];
    }
    return $randomString;
}

function generateShortLink(string $shortLinkId): string
{
    $serverName = $_SERVER['SERVER_NAME'];

    // Detect if the server supports https, to prepend the short link with it
    if (!empty($_SERVER['HTTPS'])) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    return $protocol . $serverName . '/' . $shortLinkId;
}
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
    <?php if (!empty($shortLink)): ?>
        <h1>Link shortened successfully</h1>
        <p>Your short link for <?= $url ?> is <a href="<?= $shortLink ?>"><?= $shortLink ?></a></p>
    <?php else: ?>
        <h1>Error</h1>
        <p>You must input a valid link. The length should not exceed <?= URL_MAX_LENGTH ?> characters.</p>
    <?php endif ?>
    <p><a href="/">Go to homepage</a></p>
</main>
</body>
</html>
