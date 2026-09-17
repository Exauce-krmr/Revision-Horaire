<?php
require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/../functions/classes.php";
require_once __DIR__ . "/../functions/cours.php";
require_once __DIR__ . "/../functions/crenaux.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

$method = $_SERVER["REQUEST_METHOD"];
$resource = $_GET["resource"] ?? null;
$id = $_GET["id"] ?? null;
$body = getBody();

try {

    switch ($resource) {

        case "classes":

            switch ($method) {
                case "GET":
                    if ($id) {

                        $class = getClassById($id)->fetch();

                        if (!$class) {
                            jsonResponse([KEY_MESSAGE => "class.notFound"], 404);
                        }

                        jsonResponse($class);
                    }

                    jsonResponse(getClasses()->fetchAll());

                case "POST":

                    if (!array_keys_exist($body, "name", "schoolYear")) {
                        jsonResponse([KEY_MESSAGE => "fields.missing"], 400);
                    }

                    createClass($body["name"], $body["schoolYear"]);
                    jsonResponse(getClassByName($body["name"])->fetch(), 201);

                case "DELETE":
                    if (!$id || !getClassById($id)->fetch()) {
                        jsonResponse([KEY_MESSAGE => "class.notFound"], 404);
                    }

                    deleteClass($id);

                    jsonResponse([KEY_MESSAGE => "class.deleted"]);

                default:
                    jsonResponse([KEY_MESSAGE => "method.notAllowed"], 405);
            }

        case "cours":
            switch ($method) {
                case "GET":
                    if (isset($_GET["classe"])) {
                        $class = getClassByName($_GET["classe"])->fetch();
                        if (!$class) {
                            jsonResponse([KEY_MESSAGE => "class.notFound"], 404);
                        }
                        jsonResponse([
                            "classe" => $class["nom"],
                            "annee_scolaire" => $class["annee_scolaire"],
                            "horaires" => getSlotsByClassName($_GET["classe"])->fetchAll(),
                        ]);
                    }
                    if ($id) {
                        $course = getCourseById($id)->fetch();
                        if (!$course) {
                            jsonResponse([KEY_MESSAGE => "course.notFound"], 404);
                        }
                        jsonResponse($course);
                    }

                    jsonResponse(getCourses()->fetchAll());

                case "POST":
                    if (!array_keys_exist($body, "name", "code")) {
                        jsonResponse([KEY_MESSAGE => "fields.missing"], 400);
                    }

                    createCourse($body["name"], $body["code"]);
                    $created = getCourseByCode($body["code"])->fetch();
                    jsonResponse($created, 201);

                case "DELETE":
                    if (!$id || !getCourseById($id)->fetch()) {
                        jsonResponse([KEY_MESSAGE => "course.notFound"], 404);
                    }
                    deleteCourse($id);
                    jsonResponse([KEY_MESSAGE => "course.deleted"]);

                default:
                    jsonResponse([KEY_MESSAGE => "method.notAllowed"], 405);
            }

        case "crenaux":

            switch ($method) {

                case "GET":

                    if ($id) {
                        $slot = getSlotById($id)->fetch();
                        if (!$slot) {
                            jsonResponse([KEY_MESSAGE => "slot.notFound"], 404);
                        }
                        jsonResponse($slot);
                    }
                    jsonResponse(getSlots()->fetchAll());

                case "POST":
                    if (!array_keys_exist($body, "classe", "course", "day", "startHour", "endHour", "room")) {
                        jsonResponse([KEY_MESSAGE => "fields.missing"], 400);
                    }
                    if (!Days::tryFrom($body["day"])) {
                        jsonResponse([KEY_MESSAGE => "day.invalid"], 400);
                    }
                    if (hasSlotConflict($body["classe"], $body["room"], $body["day"], $body["startHour"], $body["endHour"])) {
                        jsonResponse([KEY_MESSAGE => "slot.conflict"], 400);
                    }

                    createSlot($body["classe"], $body["course"], $body["day"], $body["startHour"], $body["endHour"], $body["room"]);
                    jsonResponse([KEY_MESSAGE => "slot.created"], 201);

                case "DELETE":
                    if (!$id || !getSlotById($id)->fetch()) {
                        jsonResponse([KEY_MESSAGE => "slot.notFound"], 404);
                    }
                    deleteSlot($id);
                    jsonResponse([KEY_MESSAGE => "slot.deleted"]);

                default:
                    jsonResponse([KEY_MESSAGE => "method.notAllowed"], 405);
            }

        default:
            jsonResponse([KEY_MESSAGE => "resource.notFound"], 404);
    }

} catch (PDOException $e) {
    jsonResponse([KEY_MESSAGE => "database.error"], 409);

} catch (Exception $e) {
    jsonResponse([KEY_MESSAGE => "server.error"], 409);
}
