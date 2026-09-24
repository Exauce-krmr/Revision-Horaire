<?php
require_once __DIR__ . "/functions/classes.php";
require_once __DIR__ . "/functions/cours.php";
require_once __DIR__ . "/functions/crenaux.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"
        && ($_POST["action"] ?? "") === "delete") {

    switch ($_POST["resource"] ?? "") {
        case "classes":
            deleteClass($_POST["id"]);
            break;
        case "cours":
            deleteCourse($_POST["id"]);
            break;
        case "crenaux":
            deleteSlot($_POST["id"]);
            break;
    }

    header("Location: index.php");
    exit();
}

$stmtClasses = getClasses();
$stmtCourses = getCourses();
$stmtSlots = getSlotsDetailed();

include __DIR__ . "/includes/header.php";
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
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php
                if ($stmtClasses->rowCount() > 0) {
                    while ($class = $stmtClasses->fetch()) { ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($class["nom"]) ?></th>
                            <td><?= htmlspecialchars($class["annee_scolaire"]) ?></td>
                            <td>
                                <a href="/pages/classes.php?id=<?= $class["id"] ?>" class="btn btn-sm btn-secondary">Modifier</a>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="resource" value="classes">
                                    <input type="hidden" name="id" value="<?= $class["id"] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                }else { ?>
                    <tr><th colspan="4">Aucune classe créer</th></tr>
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
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($stmtCourses->rowCount() > 0) {
                while ($cours = $stmtCourses->fetch()) { ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($cours["nom"]) ?></th>
                        <td><?= htmlspecialchars($cours["code"]) ?></td>
                        <td>
                            <a href="/pages/cours.php?id=<?= $cours["id"] ?>" class="btn btn-sm btn-secondary">Modifier</a>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="resource" value="cours">
                                <input type="hidden" name="id" value="<?= $cours["id"] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php }
            }else { ?>
                <tr><th colspan="4">Aucun cours créer</th></tr>
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
                <th scope="col">Classe</th>
                <th scope="col">Cours</th>
                <th scope="col">Jour</th>
                <th scope="col">Début</th>
                <th scope="col">Fin</th>
                <th scope="col">Salle</th>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($stmtSlots->rowCount() > 0) {
                while ($slot = $stmtSlots->fetch()) { ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($slot["classe_nom"]) ?></th>
                        <td><?= htmlspecialchars($slot["cours_code"]) ?> - <?= htmlspecialchars($slot["cours_nom"]) ?></td>
                        <td><?= htmlspecialchars($slot["jour"]) ?></td>
                        <td><?= htmlspecialchars($slot["heure_debut"]) ?></td>
                        <td><?= htmlspecialchars($slot["heure_fin"]) ?></td>
                        <td><?= htmlspecialchars($slot["salle"]) ?></td>
                        <td>
                            <a href="/pages/horaire.php?id=<?= $slot["id"] ?>" class="btn btn-sm btn-secondary">Modifier</a>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="resource" value="crenaux">
                                <input type="hidden" name="id" value="<?= $slot["id"] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php }
            }else { ?>
                <tr><th colspan="8">Aucun creneau créer</th></tr>
            <?php }
            ?>
            </tbody>
        </table>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>