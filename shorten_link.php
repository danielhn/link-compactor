<?php

require_once __DIR__ .  '/config.php';

$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);

if ($url && strlen($url) <= URL_MAX_LENGTH) {
    $shortLink = generateShortLink();

    echo "Your short link is $shortLink";
} else {
    echo "You must input a valid link. The length should not exceed " . URL_MAX_LENGTH . " characters.";
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

function generateShortLink(): string
{
    $shortLinkId = generateShortLinkId();
    $serverName = $_SERVER['SERVER_NAME'];

    // Detect if the server supports https, to prepend the short link with it
    if (!empty($_SERVER['HTTPS'])) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    return $protocol . $serverName . '/' . $shortLinkId;
}