<?php

class InputHelper {
    public static function loadFile($file) {
        $data = [];

        $fd = fopen($file, 'r');
        while ($row = fgets($fd, 1024)) {
            $data[] = trim($row);
        }

        return $data;
    }
}
