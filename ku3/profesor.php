<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 29. 11. 2014
 * Time: 15:52
 */

require_once 'security/Security.php';
require_once 'security/roles.php';

Security::getInstance()->requireRole(ROLE_PROFESOR);

$username = Security::getInstance()->getUsername();

$connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($connection->errno) {
    die('Connection to database failed: ' . $connection->error);
}

$predmetyQuery = 'SELECT zkratka_predmetu FROM Pedagogove_predmety WHERE kod_pedagoga = \'' . $username . '\'';
if ($predmety = $connection->query($predmetyQuery)) {
    $zoznamPredmetov = $predmety->fetch_all(MYSQLI_ASSOC);
    $predmety->free();
} else {
    die('can not query list of predmety');
}

$miestnostiQuery = 'SELECT * FROM Mistnosti';
if ($miestnosti = $connection->query($miestnostiQuery)) {
    $zoznamMiestnosti = $miestnosti->fetch_all(MYSQLI_ASSOC);
    $miestnosti->free();
} else {
    die('can not query list of miestnosti');
}

if (isset($_POST['idTerminu'])) {
    $idTerminu = $_POST['idTerminu'];
    if (isset($_POST['zrusit'])) {
        if (!($connection->query('DELETE FROM Vypsane_terminy WHERE id_terminu = \'' . $idTerminu . '\''))) {
            die('problem when deleting termin');
        }
    } else if (isset($_POST['upravit'])) {
        $terminQuery = "SELECT * FROM Vypsane_terminy WHERE id_terminu = '$idTerminu'";
        if ($termin = $connection->query($terminQuery)->fetch_assoc()) {
            echo '<form method="post"><fieldset><legend>Uprava terminu</legend>';
            echo '<input type="hidden" name="idTerminu" value="' . $idTerminu . '" />';
            echo '<p><label>Predmet';
            echo '<select name="predmet">';
            foreach ($zoznamPredmetov as &$predmet) {
                if ($predmet['zkratka_predmetu'] === $termin['zkratka_predmetu']) {
                    echo '<option selected>' . $predmet['zkratka_predmetu'] . '</option>';
                } else {
                    echo '<option>' . $predmet['zkratka_predmetu'] . '</option>';
                }
            }
            echo '</select>';
            echo '</label></p>';

            echo '<p><label>Miestnost';
            echo '<select name="miestnost">';
            foreach ($zoznamMiestnosti as &$miestnost) {
                if ($miestnost['zkratka_mistnosti'] === $termin['zkratka_mistnosti']) {
                    echo '<option selected>' . $miestnost['zkratka_mistnosti'] . '</option>';
                } else {
                    echo '<option>' . $miestnost['zkratka_mistnosti'] . '</option>';
                }
            }
            echo '</select>';
            echo '</label></p>';
            echo '<p><label>Datum';
            echo '<input name="datum" type="date" required value="' . $termin['datum_cas'] . '" />';
            echo '</label></p>';
            echo '<p><label>Max pocet prihlasenych';
            echo '<input name="maxPocet" type="number" min="0" required value="' . $termin['max_pocet_prihlasenych'] . '"/>';
            echo '</label></p>';
            echo '<p><label>Poznamka';
            echo '<input name="poznamka" type="text" value="' . $termin['poznamka'] . '" />';
            echo '</label></p>';
            echo '<p><input type="submit" name="processUpdate" value="Upravit" /></p>';
            echo '</fieldset></form>';
        } else {
            die('problem when querying your termin');
        }
    } else if (isset($_POST['processUpdate'])) {
        $updateQuery = "UPDATE Vypsane_terminy SET zkratka_mistnosti = ?, zkratka_predmetu = ?, datum_cas = ?, max_pocet_prihlasenych = ?, poznamka = ? WHERE id_terminu = $idTerminu";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bind_param('sssis', $_POST['miestnost'], $_POST['predmet'], $_POST['datum'], $_POST['maxPocet'], $_POST['poznamka']);
        if (!($stmt->execute())) {
            die('can not process your update request ' . $stmt->error);
        }
    }
} else if (isset($_POST['processCreate'])) {
    $createQuery = 'INSERT INTO Vypsane_terminy (zkratka_mistnosti, kod_pedagoga, zkratka_predmetu, datum_cas, max_pocet_prihlasenych, poznamka) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $connection->prepare($createQuery);
    $stmt->bind_param('ssssis', $_POST['miestnost'], $username, $_POST['predmet'], $_POST['datum'], $_POST['maxPocet'], $_POST['poznamka']);
    if (!($stmt->execute())) {
        die('can not process your create request ' . $stmt->error);
    }
}
?>
<table>
    <thead>
    <tr>
        <th>
            Predmet
        </th>
        <th>
            Miestnost
        </th>
        <th>
            Datum
        </th>
        <th>
            Max prihlasenych
        </th>
        <th>
            Poznamka
        </th>
        <th>
            Upravit/Zrusit
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $terminsQuery = 'SELECT * FROM Vypsane_terminy ter
WHERE ter.kod_pedagoga = \'' . $username . '\'';
    if ($termins = $connection->query($terminsQuery)) {
        while ($row = $termins->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['zkratka_predmetu'] . '</td>';
            echo '<td>' . $row['zkratka_mistnosti'] . '</td>';
            echo '<td>' . $row['datum_cas'] . '</td>';
            echo '<td>' . $row['max_pocet_prihlasenych'] . '</td>';
            echo '<td>' . $row['poznamka'] . '</td>';
            echo '<td><form method="post">';
            echo '<input type="hidden" name="idTerminu" value="' . $row['id_terminu'] . '" />';
            echo '<input type="submit" name="upravit" value="Upravit" />';
            echo '<input type="submit" name="zrusit" value="Zrusit termin" />';
            echo '</form></td>';
            echo '</tr>';
        }
        $termins->free();
    } else {
        die('problem with querying termins data');
    }
    ?>
    </tbody>
</table>

<form method="post">
    <fieldset>
        <legend>Vytvorenie terminu</legend>
        <p>
            <label>
                Predmet
                <select name="predmet">
                    <?php
                    foreach ($zoznamPredmetov as &$predmet) {
                        echo '<option>' . $predmet['zkratka_predmetu'] . '</option>';
                    }
                    ?>
                </select>
            </label>
        </p>

        <p>
            <label>
                Miestnost
                <select name="miestnost">
                    <?php
                    foreach ($zoznamMiestnosti as &$miestnost) {
                        echo '<option>' . $miestnost['zkratka_mistnosti'] . '</option>';
                    }
                    ?>
                </select>
            </label>
        </p>

        <p>
            <label>
                Datum
                <input name="datum" type="date" required/>
            </label>
        </p>

        <p>
            <label>
                Max pocet prihlasenych
                <input name="maxPocet" type="number" required/>
            </label>
        </p>

        <p>
            <label>
                Poznamka
                <input name="poznamka" type="text"/>
            </label>
        </p>

        <p>
            <input type="submit" name="processCreate" value="Vytvorit"/>
        </p>
    </fieldset>
</form>

