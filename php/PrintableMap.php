<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 16. 11. 2014
 * Time: 19:49
 */
require_once "Printable.php";

class PrintableMap implements Printable
{

    private $map;

    function PrintableMap($value)
    {
        if (is_null($value) || is_array($value)) {
            $this->map = $value;
        } else {
            throw new Exception("Parameter must be map");
        }
    }

    public function addRow($key, $value)
    {
        if (!in_array($key, $this->map)) {
            $this->map[$key] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getHtml()
    {
        if (count($this->map) > 0) {
            $result = "<table>";
            foreach ($this->map as $key => $value) {
                $result .= "<tr>";
                $result .= "<td>" . $key . "</td>";
                $result .= "<td>" . $value . "</td>";
                $result .= "</tr>";
            }
            $result .= "</table>";
            return $result;
        } else {
            return "";
        }
    }
}