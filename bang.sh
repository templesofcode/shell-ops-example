<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Output\ConsoleOutput;
use TemplesOfCode\TestShellOperations;

$out = new ConsoleOutput();
$out->writeln('Lets start..');
$c = new TestShellOperations($out);
$c->run();
