#!/usr/bin/php
<?php

/**
 * sublime-svn-customdiff
 *
 * this little script allows you to start a custom diff tool.
 * it parses the default sublime svn plugin's arguments passed to default diff.
 * those arguments are not compatible with some diff tools like diffuse.
 * so it needs to be reformated and thats what this script does.
 *
 * copy this script to any place where you want to run it (you may remove file extension).
 * set executable flag to it `sudo chmod +x sublime-svn-customdiff`.
 * don't forget to set proper rights so sublime svn can run it.
 *
 * set sublime svn setting:
 *   ,"diff_command": "/path/to/sublime-svn-customdiff"
 *
 * done, she'll be right!
 *
 * @version   0.0.1-2014.01.25
 * @author    CodeXP <codexp@gmx.net>
 * @license   GPL License
 * @copyright Copyright (C) 2014 by CodeXP
 */

error_reporting(E_ALL);
ob_start();

/*
 * report errors to this recipients
 */
$report    = true;
$report_to = array(
    'your name' => 'your@email.com',
);

$workingCopy = false;
$log         = false;
$rev         = array();

// remove script's filename
array_shift($argv);

// extract full file name
$file = array_pop($argv);

/*
 * parse arguments
 */
foreach( $argv as $arg ) {
    /*
     * parse revision
     */
    if( preg_match('/^(.+)\s+\(revision\s+r?(\d+)\)$/i', $arg, $m) ) {
        // $m[1] - file name (relative path)
        // $m[2] - revision number
        $rev[] = $m[2];
    }
    /*
     * parse working copy
     */
    if( preg_match('/^(.+)\s+\(working copy\)$/i', $arg, $m) ) {
        // $m[1] - file name (relative path)
        $workingCopy = true;
    }
}

if( count($rev) === 1 ) {
    $workingCopy = true;
}

if( $workingCopy && $rev ) {
    $rev = array_pop($rev);

    // reassemble command and arguments
    $cmd = '/usr/bin/diffuse -r ' . $rev . ' ' . $file;

    // run custom diff tool
    $res = exec($cmd, $out, $ret);

    // if there has been output by command (perhaps something went wrong?)
    if( $out ) {
        $log = 'cmd:' . $cmd . PHP_EOL
             . 'cmd return:' . var_export($ret, true) . PHP_EOL
             . 'cmd output:' . PHP_EOL . implode(PHP_EOL, $out) . PHP_EOL;
    }

} else {
    // if we have 2 or more revisions to compare
    if( count($rev) > 1 ) {
        $log = 'cant handle multiple revisions yet!' . PHP_EOL
             . 'revisions:' . json_encode($rev) . PHP_EOL;
    } else {
        $log = 'something went wrong!' . PHP_EOL
             . 'argv:' . implode(' ', $argv) . PHP_EOL
             . 'file:' . $file . PHP_EOL
             . 'revisions:' . json_encode($rev) . PHP_EOL;
    }

}

// if there has been php output (something went wrong?)
if( $buffer = ob_get_contents() ) {
    $log .= 'php output:' . PHP_EOL . $buffer . PHP_EOL;
}

// if there was somethig ...
if( $report && $log && $report_to ) {
    $to = array();
    foreach( $report_to as $name => $email ) {
        if( is_string($name) ) {
            $email = "$name <$email>";
        }
        $to[] = $email;
    }
    // report log
    mail(implode(', ', $to), 'sublime-svn-customdiff', $log);
}

ob_end_clean();
