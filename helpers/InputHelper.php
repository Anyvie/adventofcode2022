<?php

class InputHelper {
    public static function loadFile($file, $trimCharacters=" \n\r\t\v\x00") {
        $data = [];

        $fd = fopen($file, 'r');
        while ($row = fgets($fd, 1024)) {
            $data[] = trim($row, $trimCharacters);
        }

        return $data;
    }
}
