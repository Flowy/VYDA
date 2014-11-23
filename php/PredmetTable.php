<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 19. 11. 2014
 * Time: 20:49
 */
require_once 'Printable.php';
require_once 'PrintableMap.php';

class PredmetTable implements Printable
{

    private static $keys = array('zkratka', 'nazov', 'rozsah prednasek', 'rozsah cviceni', 'pocet kreditu', 'ukonceni', 'popis');
    private $db;
    private $tableData = array();

    function PredmetTable(mysqli $db)
    {
        $this->db = $db;
    }

    public function getHtml()
    {
        $this->fetchData();

        if (count($this->tableData) > 0) {
            $html = '<table>';
            $html .= '<thead><tr>';
            foreach (self::$keys as $column) {
                $html .= '<td>' . $column . '</td>';
            }
            $html .= '</tr></thead>';

            foreach ($this->tableData as $row) {
                $html .= '<tr>';
                for ($i = 0; $i < count($row); $i++) {
                    $html .= '<td>' . $row[$i] . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html = 'Ziadne data v tabulke';
        }


        return $html;
    }

    private function fetchData()
    {
        $selectQuery = 'SELECT * FROM predmet';
        $queryResult = $this->db->query($selectQuery);

        while ($row = $queryResult->fetch_row()) {
            $this->tableData[] = $row;
        }
        $queryResult->close();
    }
}