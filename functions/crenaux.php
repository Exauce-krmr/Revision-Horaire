<?php require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/../connexion/db.php";
require_once __DIR__ . "/../utils/sanitizeInput.php";

$db = getDb();

function getSlots()
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM crenaux");
    $stmt->execute();
    return $stmt;
}

function getSlotById($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM crenaux WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt;
}

function deleteSlot($id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM crenaux WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt;
}

function getSlotsByClassName($className)
{
    global $db;
    $stmt = $db->prepare("SELECT crenaux.jour,
                                  DATE_FORMAT(crenaux.heure_debut, '%H:%i') AS heure_debut,
                                  DATE_FORMAT(crenaux.heure_fin, '%H:%i') AS heure_fin,
                                  cours.nom AS cours, cours.code AS code_cours, crenaux.salle
                           FROM crenaux
                           JOIN classes ON classes.id = crenaux.classe_id
                           JOIN cours ON cours.id = crenaux.cours_id
                           WHERE classes.nom = :nom
                           ORDER BY FIELD(crenaux.jour, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'), crenaux.heure_debut");
    $stmt->execute(["nom" => $className]);
    return $stmt;
}

function hasSlotConflict($classId, $room, $day, $startHour, $endHour)
{
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) FROM crenaux
                           WHERE jour = :jour
                             AND heure_debut < :heure_fin
                             AND heure_fin > :heure_debut
                             AND (classe_id = :classe_id OR salle = :salle)");
    $stmt->execute([
        "jour" => $day,
        "heure_debut" => $startHour,
        "heure_fin" => $endHour,
        "classe_id" => $classId,
        "salle" => $room,
    ]);
    return $stmt->fetchColumn() > 0;
}

function createSlot($classId, $courseId, $day, $startHour, $endHour, $room)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO crenaux (classe_id, cours_id, jour, heure_debut, heure_fin, salle)
                           VALUES (:classe_id, :cours_id, :jour, :heure_debut, :heure_fin, :salle)");
    $stmt->execute([
        "classe_id" => $classId,
        "cours_id" => $courseId,
        "jour" => $day,
        "heure_debut" => $startHour,
        "heure_fin" => $endHour,
        "salle" => $room,
    ]);
    return $stmt;
}

function handleSlotForm()
{
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return $errors;
    }

    $classId = filter_input(INPUT_POST, "classe", FILTER_VALIDATE_INT);
    $courseId = filter_input(INPUT_POST, "course", FILTER_VALIDATE_INT);
    $day = sanitizeInput($_POST["day"] ?? "", Type::string);
    $startHour = sanitizeInput($_POST["startHour"] ?? "", Type::string);
    $endHour = sanitizeInput($_POST["endHour"] ?? "", Type::string);
    $room = sanitizeInput($_POST["room"] ?? "", Type::string);

    if (!$classId) {
        $errors["classe"] = "Veuilliez de choisir une classe.";
    }

    if (!$courseId) {
        $errors["course"] = "Veuilliez de choisir un cours.";
    }

    if (!Days::tryFrom($day)) {
        $errors["day"] = "Veuilliez de choisir un jour valide.";
    }

    if ($startHour === "") {
        $errors["startHour"] = "L'heure de début est obligatoire.";
    }

    if ($endHour === "") {
        $errors["endHour"] = "L'heure de fin est obligatoire.";
    } elseif ($startHour !== "" && $endHour <= $startHour) {
        $errors["endHour"] = "L'heure de fin doit être après l'heure de début.";
    }

    if ($room === "") {
        $errors["room"] = "La salle est obligatoire.";
    } elseif (strlen($room) > 20) {
        $errors["room"] = "Le nom de la salle ne peut pas dépasser 20 caractères.";
    }

    if (!$errors && hasSlotConflict($classId, $room, $day, $startHour, $endHour)) {
        $errors["room"] = "Ce créneau chevauche déjà un cours pour cette classe ou cette salle.";
    }

    if (!$errors) {
        createSlot($classId, $courseId, $day, $startHour, $endHour, $room);
        header("Location: horaire.php");
        exit;
    }

    return $errors;
}