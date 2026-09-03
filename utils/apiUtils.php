<?php declare(strict_types=1);

/**
 * Verifieque toutes les clé existe dans l'array
 */
function array_keys_exist($array, ...$keys)
{
    foreach ($keys as $key) {
        if (!array_key_exists($key, $array)) {
            return false;
        }
    }
    return true;
}


function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}



function getTokenOrHalt()
{
    $token = getToken();
    return $token ?: jsonResponse([KEY_MESSAGE => "token.missing"], 401);
}

function getToken()
{
    $auth = getHeader("Authorization");
    if ($auth == null) {
        return null;
    }
    $auth = explode(" ", $auth);
    if ($auth[0] != "Token") {
        return null;
    }

    return $auth[1];
}

function getHeader($key)
{
    foreach (getallheaders() as $name => $value) {
        if (strtolower($key) === strtolower($name)) {
            return $value;
        }
    }
    return null;
}

function getBody()
{
    $bodyRaw = file_get_contents("php://input");

    if (empty($bodyRaw)) {
        return [];
    }

    $data = json_decode($bodyRaw, true);

    if (!is_array($data)) {

        http_response_code(400);

        echo json_encode([KEY_MESSAGE => "json.invalid"]);

        exit();

    }

    return $data;
}

function getId(string $nameParam = KEY_ID)
{
    $idAd = filter_input(INPUT_GET, $nameParam, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if (!$idAd) {
        jsonResponse([KEY_MESSAGE => KEY_ID . ".invalid"], 400);
    }
    return (int)$idAd;
}

function jsonResponse(?array $data, int $responseCode = 200)
{
    http_response_code($responseCode);
    if ($data !== null) {
        echo json_encode($data);
    }
    exit();
}