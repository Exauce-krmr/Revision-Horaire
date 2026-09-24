<?php
require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/../connexion/db.php";
require_once __DIR__ . "/../utils/sanitizeInput.php";

$db = getDb();

function getClasses()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM classes");
    $stmt->execute();
    return $stmt;
}

function getClassByName($name)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM classes WHERE nom = :nom");
    $stmt->execute(["nom" => $name]);
    return $stmt;
}

function getClassById($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM classes WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt;
}

function createClass($name, $schoolYear)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO classes (nom, annee_scolaire) VALUES (:nom, :annee_scolaire)");
    $stmt->execute(["nom" => $name, "annee_scolaire" => $schoolYear]);
    return $stmt;
}

function updateClass($id, $name, $schoolYear)
{
    global $db;
    $stmt = $db->prepare("UPDATE classes SET nom = :nom, annee_scolaire = :annee_scolaire WHERE id = :id");
    $stmt->execute(["nom" => $name, "annee_scolaire" => $schoolYear, "id" => $id]);
    return $stmt;
}

function deleteClass($id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM classes WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt;
}

define("CLASS_NAME_MAX_LENGHT",  50);

function handleClassForm()
{
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return $errors;
    }

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $name = sanitizeInput($_POST["name"] ?? "", Type::string);
    $schoolYear = sanitizeInput($_POST["schoolYear"] ?? "", Type::string);

    if ($name === "") {
        $errors["name"] = "Le nom de la classe est obligatoire.";
    } elseif (strlen($name) > CLASS_NAME_MAX_LENGHT) {
        $errors["name"] = "Le nom de la classe ne doit pas dépasser " . CLASS_NAME_MAX_LENGHT . " caractères.";
    } else {
        $existing = getClassByName($name)->fetch();
        if ($existing && (!$id || $existing["id"] != $id)) {
            $errors["name"] = "Cette classe existe déjà.";
        }
    }

    if ($schoolYear === "") {
        $errors["schoolYear"] = "L'année scolaire est obligatoire.";
    }

    if (!$errors) {
        if ($id) {
            updateClass($id, $name, $schoolYear);
            header("Location: /index.php");
        } else {
            createClass($name, $schoolYear);
            header("Location: classes.php");
        }
        exit;
    }

    return $errors;
}
