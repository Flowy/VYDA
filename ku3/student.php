<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 29. 11. 2014
 * Time: 15:51
 */
require_once 'security/Security.php';
require_once 'security/roles.php';

Security::getInstance()->requireRole(ROLE_STUDENT);

$username = Security::getInstance()->getUsername();

$connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($connection->errno) {
    die('Connection to database failed: ' . $connection->error);
}

if (isset($_POST['idTerminu'])) {
    if (isset($_POST['prihlasit'])) {
        if (!($connection->query('INSERT INTO Zapsane_terminy (id_terminu, kod_studenta) VALUES (\'' . $_POST['idTerminu'] . '\', \'' . $username . '\')'))) {
            die('problem when processing registering to termin');
        }
    } else if (isset($_POST['odhlasit'])) {
        if (!($connection->query('DELETE FROM Zapsane_terminy WHERE id_terminu = \'' . $_POST['idTerminu'] . '\' AND kod_studenta = \'' . $username . '\''))) {
            die ('problem when processing unregistering to termin');
        }
    } else {
        die('can not process your request');
    }
}

$termsQuery =
    'SELECT * FROM Vypsane_terminy ter
WHERE ter.zkratka_predmetu IN (SELECT zkratka_predmetu FROM Predmety pre
WHERE pre.zkratka_predmetu IN (SELECT zkratka_predmetu FROM Studenti_predmety
WHERE kod_studenta = \'' . $username . '\'))';

if (!($terms = $connection->query($termsQuery))) {
    die('problem with querying exam terms from database');
}
;

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
                Prihlasit/Odhlasit
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $terms->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['zkratka_predmetu'] . '</td>';
            echo '<td>' . $row['zkratka_mistnosti'] . '</td>';
            echo '<td>' . $row['datum_cas'] . '</td>';
            $zapsaneQuery = 'SELECT * FROM Zapsane_terminy WHERE kod_studenta = \'' . $username . '\' AND id_terminu = \'' . $row['id_terminu'] . '\'';
            if ($zapsanyResult = $connection->query($zapsaneQuery)) {
                echo '<td><form method="post">';
                echo '<input type="hidden" name="idTerminu" value="' . $row['id_terminu'] . '" />';
                $zapsanyTermin = $zapsanyResult->fetch_assoc();
                if ($zapsanyTermin) {
                    echo '<input type="submit" name="odhlasit" value="Odhlasit" />';
                } else {
                    echo '<input type="submit" name="prihlasit" value="Prihlasit" />';
                }
                echo '</form></td>';
                $zapsanyResult->free();
            } else {
                die('problem with querying zapsane terminy for ' . $row['id_terminu']);
            }
            echo '</tr>';
        }
        $terms->free();
        ?>
        </tbody>
    </table>

<?php
$connection->close();
