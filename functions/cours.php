<?php require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/../connexion/db.php";
require_once __DIR__ . "/../utils/sanitizeInput.php";

$db = getDb();

function getCourses()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM cours");
    $stmt->execute();
    return $stmt;
}

function getCourseByCode($code)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM cours WHERE code = :code");
    $stmt->execute(["code" => $code]);
    return $stmt;
}

function getCourseById($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM cours WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt;
}

function createCourse($name, $code)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO cours (nom, code) VALUES (:nom, :code)");
    $stmt->execute(["nom" => $name, "code" => $code]);
    return $stmt;
}

function updateCourse($id, $name, $code)
{
    global $db;
    $stmt = $db->prepare("UPDATE cours SET nom = :nom, code = :code WHERE id = :id");
    $stmt->execute(["nom" => $name, "code" => $code, "id" => $id]);
    return $stmt;
}

function deleteCourse($id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM cours WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt;
}

define("COURSE_NAME_MAX_LENGHT",  120);
define("COURSE_CODE_MAX_LENGHT",  20);

function handleCourseForm()
{
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return $errors;
    }

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $name = sanitizeInput($_POST["name"] ?? "", Type::string);
    $code = sanitizeInput($_POST["code"] ?? "", Type::string);

    if ($name === "") {
        $errors["name"] = "Le nom du cours est obligatoire.";
    } elseif (strlen($name) > COURSE_NAME_MAX_LENGHT) {
        $errors["name"] = "Le nom du cours ne peut pas dépasser " . COURSE_NAME_MAX_LENGHT . " caractères.";
    }

    if ($code === "") {
        $errors["code"] = "Le code du cours est obligatoire.";
    } elseif (strlen($code) > COURSE_CODE_MAX_LENGHT) {
        $errors["code"] = "Le code du cours ne peut pas dépasser " . COURSE_CODE_MAX_LENGHT . " caractères.";
    } else {
        $existing = getCourseByCode($code)->fetch();
        if ($existing && (!$id || $existing["id"] != $id)) {
            $errors["code"] = "Ce code de cours existe déjà.";
        }
    }

    if (!$errors) {
        if ($id) {
            updateCourse($id, $name, $code);
            header("Location: /index.php");
        } else {
            createCourse($name, $code);
            header("Location: cours.php");
        }
        exit;
    }

    return $errors;
}