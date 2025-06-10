<?php
include('functions.php');
extract($_POST);

if (!isset($url)) {
    echo json_encode(["status" => 0, "message" => "Url is Required"]);
    die();
}

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(["status" => 0, "message" => "Invalid Url Format"]);
    die();
}

if (empty($vanityUrl)) {
    $vanityUrl = isUrlExists($url);
    if (!empty($vanityUrl)) {
        echo json_encode(["status" => 1, "message" => "Alias Generated Successfully", "url" => $domainUrl . $vanityUrl]);
        die();
    }
    $vanityUrl = generateUniqueAlias();
    addAlias($url, $vanityUrl);
    echo json_encode(["status" => 1, "message" => "Alias Generated Successfully", "url" => $domainUrl . $vanityUrl]);
    die();
} else {
    $len = strlen($vanityUrl);
    if ($len < 3) {
        echo json_encode(["status" => 0, "message" => "Enter at least 3 characters for Alias"]);
        die();
    }
    $pattern = "/^[a-zA-Z0-9\-]+$/";
    if (!preg_match($pattern, $vanityUrl)) {
        echo json_encode(["status" => 0, "message" => "Invalid Alias Format"]);
        die();
    }
    if (isAliasExists($vanityUrl)) {
        echo json_encode(["status" => 0, "message" => "Alias Already Exists"]);
        die();
    }
    addAlias($url, $vanityUrl);
    echo json_encode(["status" => 1, "message" => "Alias Added Successfully", "url" => $domainUrl . $vanityUrl]);
    die();
}