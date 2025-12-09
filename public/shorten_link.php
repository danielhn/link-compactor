<?php

require_once '../config.php';
require_once '../env.php';

$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);

if ($url && strlen($url) <= URL_MAX_LENGTH) {
    $db = new PDO(SQLITE_DATABASE_PATH);

    $url = htmlspecialchars($url);

    $shortLinkId = getShortLinkIdIfExists($db, $url);

    if (empty($shortLinkId)) {
        $shortLinkId = generateShortLinkId();

        $sql = "INSERT INTO " . DATABASE_TABLE_NAME . " (id, url) VALUES (:id, :url)";

        $db->prepare($sql)->execute([$shortLinkId, $url]);
    }

    $shortLink = generateShortLink($shortLinkId);
}

function getShortLinkIdIfExists(PDO $db, string $url): string | null
{
    $sql = "SELECT id FROM " . DATABASE_TABLE_NAME . " WHERE url = ? LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->execute([$url]);
    $shortLinkId = $stmt->fetch(PDO::FETCH_ASSOC);
    return $shortLinkId['id'];
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

    if ($_SERVER['SERVER_PORT'] !== 80 && $_SERVER['SERVER_PORT'] !== 443) {
        $fullShortLink = $protocol . $serverName . ':' .  $_SERVER['SERVER_PORT'] . '/' . $shortLinkId;
    } else {
        $fullShortLink = $protocol . $serverName . '/' . $shortLinkId;
    }

    return $fullShortLink;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link shortener</title>
    <link rel="stylesheet" href="style.css">
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
