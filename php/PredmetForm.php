<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 19. 11. 2014
 * Time: 19:41
 */

require_once "Printable.php";
require_once "Form.php";

class PredmetForm implements Printable, Form
{

    private $dbConnection;

    function PredmetForm(mysqli $dbConnection, array $params)
    {
        $this->dbConnection = $dbConnection;
        $this->parseParameters($params);
    }

    public function parseParameters(array $params)
    {
        $requiredParams = array('zkratka');
        if (!$this->checkRequiredParams($params, $requiredParams) || is_null($this->dbConnection)) {
            return;
        }

        $rozsah_prednasek = $this->getSmallInt('rozsah_prednasek');
        $rozsah_cviceni = $this->getSmallInt('rozsah_cviceni');
        $pocet_kreditu = $this->getSmallInt('pocet_kreditu');

        if ($stmt = $this->dbConnection->prepare('INSERT INTO `predmet`(`zkratka`,`nazov`,`rozsah_prednasek`,`rozsah_cviceni`,`pocet_kreditu`,`ukonceni`,`popis`) VALUES (?, ?, ?, ?, ?, ?, ?)')) {
            $stmt->bind_param('ssiiiss', $params['zkratka'], $params['nazov'], $rozsah_prednasek, $rozsah_cviceni, $pocet_kreditu, $params['ukonceni'], $params['popis']);
            if ($stmt->execute()) {
                print('parameters was saved into database');
            } else {
                print('error while inserting into db: ' . $stmt->error);
            }
        }
    }

    private function checkRequiredParams(array $params, array $requiredParams)
    {
        foreach ($requiredParams as $required) {
            if (!array_key_exists($required, $params)) {
                return false;
            }
        }
        return true;
    }

    private function getSmallInt($paramName)
    {
        return filter_input(INPUT_GET, $paramName, FILTER_VALIDATE_INT, array('options' => array('min_range' => -32768, 'max_range' => 32767)));
    }

    public function getHtml()
    {
        return file_get_contents('predmetForm.html');
    }
}