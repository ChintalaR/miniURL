<?php
include('config.php');

function generateRandomAlias()
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomAlias = '';
    for ($i = 0; $i < 10; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $randomAlias .= $characters[$randomIndex];
    }
    return $randomAlias;
}
function isAliasExists($alias)
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM urls WHERE alias = ?");
    $stmt->bind_param("s", $alias);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

function generateUniqueAlias()
{
    do {
        $randomString = generateRandomAlias();
    } while (isAliasExists($randomString));

    return $randomString;
}

function addAlias($url, $vanityUrl)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO urls (alias,url) VALUES (?,?)");
    $stmt->bind_param("ss", $vanityUrl, $url);
    $stmt->execute();
}


function isUrlExists($url)
{
    global $conn;
    $stmt = $conn->prepare("SELECT alias FROM urls WHERE url = ? LIMIT 1");
    $stmt->bind_param("s", $url);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['alias'];
    }

    return false;
}

function redirect($str)
{
    global $conn;
    $stmt = $conn->prepare("SELECT url FROM urls where alias =?");
    $stmt->bind_param("s", $str);
    $stmt->execute();
    $stmt->bind_result($url);
    $stmt->fetch();
    if (!empty($url)) {
        echo "Redirecting...";
        header("Location: $url");
        exit;
    } else {
        echo "Invalid Url";
    }
}