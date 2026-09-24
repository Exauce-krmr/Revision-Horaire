<?php
require_once __DIR__ . "/../functions/cours.php";

$editId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$editCourse = $editId ? getCourseById($editId)->fetch() : null;

$errors = handleCourseForm();

include __DIR__ . "/../includes/header.php";
?>

    <form method="post">

        <?php if ($editCourse) { ?>
            <input type="hidden" name="id" value="<?= $editCourse["id"] ?>">
        <?php } ?>

        <div class="mb-3">
            <label for="name" class="form-label">Nom du cours</label>
            <input class="form-control <?= isset($errors["name"]) ? "is-invalid" : "" ?>"
                   name="name" id="name" type="text" minlength="1" maxlength="120" required
                   value="<?= htmlspecialchars($_POST["name"] ?? ($editCourse["nom"] ?? "")) ?>"
            >
            <?php if (isset($errors["name"])) { ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors["name"]) ?></div>
            <?php } ?>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input class="form-control <?= isset($errors["code"]) ? "is-invalid" : "" ?>"
                   name="code" id="code" type="text" minlength="1" maxlength="20" required
                   value="<?= htmlspecialchars($_POST["code"] ?? ($editCourse["code"] ?? "")) ?>"
            >

            <?php if (isset($errors["code"])) { ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors["code"]) ?></div>
            <?php } ?>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= $editCourse ? "Enregistrer" : "Submit" ?>
        </button>
    </form>

<?php include __DIR__ . "/../includes/footer.php"; ?>