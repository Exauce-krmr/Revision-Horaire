<?php
include __DIR__ . "/includes/header.php";
require_once __DIR__ . "/functions/classes.php";
require_once __DIR__ . "/functions/cours.php";
require_once __DIR__ . "/functions/crenaux.php";

$stmtClasses = getClasses();
$stmtCourses = getCourses();
$stmtSlots = getSlots();

?>

<main>

    <h1>Bienvenue</h1>
    <hr>
    <section>
        <h2>Classes (<?= $stmtClasses->rowCount() ?>)</h2>
        <table class="table table-striped-columns">
            <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Annee scolaire</th>
            </tr>
            </thead>
            <tbody>
            <?php
                if ($stmtClasses->rowCount() > 0) {
                    while ($class = $stmtClasses->fetch()) { ?>
                        <tr>
                            <th scope="row"><?= $class["nom"] ?></th>
                            <td><?= $class["annee_scolaire"] ?></td>
                        </tr>
                    <?php }
                }else { ?>
                    <tr><th colspan="2">Aucune classe créer</th></tr>
                <?php }
            ?>
            </tbody>
        </table>
    </section>
    <br>
    <section>
        <h2>Cours (<?= $stmtCourses->rowCount() ?>)</h2>
        <table class="table table-striped-columns">
            <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Code</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($stmtCourses->rowCount() > 0) {
                while ($cours = $stmtCourses->fetch()) { ?>
                    <tr>
                        <th scope="row"><?= $cours["nom"] ?></th>
                        <td><?= $cours["code"] ?></td>
                    </tr>
                <?php }
            }else { ?>
                <tr><th colspan="2">Aucun cours créer</th></tr>
            <?php }
            ?>
            </tbody>
        </table>
    </section>
    <br>
    <section>
        <h2>Crenaux (<?= $stmtSlots->rowCount() ?>)</h2>
        <table class="table table-striped-columns">
            <thead>
            <tr>
                <th scope="col">Jour</th>
                <th scope="col">Salle</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($stmtSlots->rowCount() > 0) {
                while ($slot = $stmtSlots->fetch()) { ?>
                    <tr>
                        <th scope="row"><?= $slot["jour"] ?></th>
                        <td><?= $slot["salle"] ?></td>
                    </tr>
                <?php }
            }else { ?>
                <tr><th colspan="2">Aucun crenaux créer</th></tr>
            <?php }
            ?>
            </tbody>
        </table>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>