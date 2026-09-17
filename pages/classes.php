<?php
require_once __DIR__ . "/../functions/classes.php";

$errors = handleClassForm();

include __DIR__ . "/../includes/header.php";
?>

<form method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Nom de la classe</label>
        <input class="form-control <?= isset($errors["name"]) ? "is-invalid" : "" ?>" name="name" id="name" type="text" value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">
        <?php if (isset($errors["name"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["name"]) ?></div>
        <?php } ?>
    </div>
    <div class="mb-3">
        <label for="schoolYear" class="form-label">Annee scolaire</label>
        <input class="form-control <?= isset($errors["schoolYear"]) ? "is-invalid" : "" ?>" name="schoolYear" id="schoolYear" type="text" value="<?= htmlspecialchars($_POST["schoolYear"] ?? "") ?>">
        <?php if (isset($errors["schoolYear"])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors["schoolYear"]) ?></div>
        <?php } ?>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php include __DIR__ . "/../includes/footer.php"; ?>