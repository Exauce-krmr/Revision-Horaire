<?php
require_once __DIR__ . "/../functions/classes.php";
require_once __DIR__ . "/../functions/cours.php";
require_once __DIR__ . "/../functions/crenaux.php";

$editId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$editSlot = $editId ? getSlotById($editId)->fetch() : null;

$errors = handleSlotForm();
$classes = getClasses()->fetchAll();
$courses = getCourses()->fetchAll();

$selectedClasse = $_POST["classe"] ?? ($editSlot["classe_id"] ?? "");
$selectedCourse = $_POST["course"] ?? ($editSlot["cours_id"] ?? "");
$selectedDay = $_POST["day"] ?? ($editSlot["jour"] ?? "");
$startHourValue = $_POST["startHour"] ?? (isset($editSlot["heure_debut"]) ? substr($editSlot["heure_debut"], 0, 5) : "");
$endHourValue = $_POST["endHour"] ?? (isset($editSlot["heure_fin"]) ? substr($editSlot["heure_fin"], 0, 5) : "");
$roomValue = $_POST["room"] ?? ($editSlot["salle"] ?? "");

include __DIR__ . "/../includes/header.php";
?>

<form method="post">

    <?php if ($editSlot) { ?>
        <input type="hidden" name="id" value="<?= $editSlot["id"] ?>">
    <?php } ?>

    <div class="mb-3">
        <label for="classe" class="form-label">Classe</label>
        <select class="form-select <?= isset($errors["classe"]) ? "is-invalid" : "" ?>" name="classe" id="classe" required>
            <?php foreach ($classes as $class) { ?>
                <option value="<?= $class["id"] ?>" <?= $selectedClasse == $class["id"] ? "selected" : "" ?>>
                    <?= htmlspecialchars($class["nom"]) ?>
                </option>
            <?php } ?>
        </select>
        <?php if (isset($errors["classe"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["classe"]) ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label for="course" class="form-label">Cours</label>
        <select class="form-select <?= isset($errors["course"]) ? "is-invalid" : "" ?>" name="course" id="course" required>
            <?php foreach ($courses as $course) { ?>
                <option value="<?= $course["id"] ?>" <?= $selectedCourse == $course["id"] ? "selected" : "" ?>><?= htmlspecialchars($course["nom"]) ?></option>
            <?php } ?>
        </select>
        <?php if (isset($errors["course"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["course"]) ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label for="day" class="form-label">Jour</label>
        <select class="form-select <?= isset($errors["day"]) ? "is-invalid" : "" ?>" name="day" id="day" required>
            <?php
                foreach (Days::cases() as $dayOption) { ?>
                    <option value="<?= $dayOption->value ?>" <?= $selectedDay === $dayOption->value ? "selected" : "" ?>><?= $dayOption->value; ?></option>
                <?php }
            ?>
        </select>
        <?php if (isset($errors["day"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["day"]) ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label for="startHour" class="form-label">Heure de début</label>
        <input class="form-control <?= isset($errors["startHour"]) ? "is-invalid" : "" ?>"
               name="startHour" id="startHour" type="time" min="08:05" max="17:05" required
               value="<?= htmlspecialchars($startHourValue) ?>"
        >
        <?php if (isset($errors["startHour"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["startHour"]) ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label for="endHour" class="form-label">Heure de fin</label>
        <input class="form-control <?= isset($errors["endHour"]) ? "is-invalid" : "" ?>"
               name="endHour" id="endHour" type="time" min="08:05" max="17:50" required
               value="<?= htmlspecialchars($endHourValue) ?>"
        >
        <?php if (isset($errors["endHour"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["endHour"]) ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label for="room" class="form-label">Salle</label>
        <input class="form-control <?= isset($errors["room"]) ? "is-invalid" : "" ?>"
               name="room" id="room" type="text" minlength="1" maxlength="20" required
               value="<?= htmlspecialchars($roomValue) ?>"
        >
        <?php if (isset($errors["room"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["room"]) ?></div>
        <?php } ?>
    </div>
    <button type="submit" class="btn btn-primary">
        <?= $editSlot ? "Enregistrer" : "Submit" ?>
    </button>
</form>

<?php include __DIR__ . "/../includes/footer.php"; ?>