#!/usr/bin/env php
<?php

require_once('../vendor/autoload.php');

class worker extends \Octris\Process\Child {
    function run() {
        while (true) {
            if (($msg = $this->messaging->recv()) !== false) {
                $this->messaging->send(strrev($msg));
            }
        }
    }
}

$proc = new \Octris\Process();
$child = $proc->fork('worker');

$cnt = 1;

while ($cnt < 4) {
    $child->send('Test #' . $cnt);

    do {
        if (($msg = $child->recv()) !== false) {
            print trim($msg) . "\n";
            break;
        }
    } while(true);

    ++$cnt;
}
