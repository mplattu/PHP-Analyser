<?php

require_once 'config.php';
require_once 'Parser.php';

if (DEBUG) {
    $p = new Parser(Detect::All);
    $p->parse('test.php', Options::None);
} else {
    $commands = [
        'detect-all',
        'no-init',
        'undef',
        'unused',
        'spell',
        'verbose',
        'debug',
    ];

    $cmd = getopt(null, $commands);

    $options = Options::None;
    $detect  = Detect::None;

    foreach ($cmd as $key => $_) {
        $index = array_search('--' . $key, $argv);
        unset($argv[$index]);

        switch ($key) {
            case 'detect-all':
                $detect = Detect::All;
            break;
            case 'no-init':
                $detect |= Detect::Uninitialized;
            break;
            case 'undef':
                $detect |= Detect::Undefined;
            break;
            case 'unused':
                $detect |= Detect::Unused;
            break;
            case 'spell':
                $detect |= Detect::PossibleMisspelling;
            break;
            case 'verbose':
                $options |= Options::Verbose;
            break;
            case 'debug':
                $options |= Options::Debug;
            break;
        }
    }

    array_shift($argv); // remove main.php

    if ($detect != Detect::None && !empty($argv)) {
        $p = new Parser($detect);
        foreach ($argv as $arg) {
            if (is_file($arg) && substr($arg, -4) == '.php') {
                $p->parse($arg, $options);
            }
        }
    }
}
