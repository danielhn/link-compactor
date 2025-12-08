<?php
require_once __DIR__ .  '/config.php';
require_once __DIR__ . '/env.php';

if ($_SERVER['REQUEST_URI'] !== '/index.php' && $_SERVER['REQUEST_URI'] !== '/') {
    $requestURI = explode('/', $_SERVER['REQUEST_URI']);

    // First string in path should be short link id
    $shortLinkId = $requestURI[1];
    if (validateShortLinkId($shortLinkId)) {
        $db = new PDO(SQLITE_DATABASE_PATH);

        $sql = "SELECT url FROM linkshortener WHERE id = ? LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([$shortLinkId]);
        $originalLink = $stmt->fetch(PDO::FETCH_ASSOC);
        $url = $originalLink['url'];
    } else {
        $pageNotFound = true;
    }

}

function validateShortLinkId(string $shortLinkId): false|int
{
    return preg_match("/^[a-zA-Z0-9]{" . SHORT_LINK_ID_LENGTH . "}$/", $shortLinkId);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Link shortener</title>
</head>
<body>
    <main>
        <?php if (empty($url) && empty($pageNotFound)): ?>
        <h1>Link Shortener</h1>
        <form action="./shorten_link.php" method="post">
            <div>
                <label for="url">Link to shorten</label>
                <input type="url" name="url" id="url" maxlength="<?= URL_MAX_LENGTH ?>" required>
            </div>
            <button type="submit">Create short link</button>
        </form>
        <?php elseif (!empty($url)): ?>
        <h1>Check the link first</h1>
            <p>You are going to <a rel="noopener noreferer nofollow" href="<?= $url ?>"><?= $url ?></a>.
                If you want to continue, click on the link.
            </p>
        <p>Or you can <a href="/">go back to the homepage</a>.</p>
        <?php elseif (!empty($pageNotFound)): ?>
            <?php http_response_code(404) ?>
            <h1>The page you tried to go doesn't exist</h1>
            <p><a href="/">Go back to the homepage</a>.</p>
        <?php endif ?>
    </main>
</body>
</html>
