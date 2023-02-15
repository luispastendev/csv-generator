<?php

use CSVGenerator\CSVGenerator;

it('add suffix to a name', function () {
    $filename  = 'test';
    $method = getPrivateMethod(new CSVGenerator, 'addSuffix');

    for ($i = 1; $i <= 3; $i++) {
        $filename = $method($filename);
        expect($filename)->toBe("test_{$i}");
    }
});

it('generate filename with suffix', function () {
    $path  = __DIR__ . '/test.csv';
    $method = getPrivateMethod(new CSVGenerator, 'getFilenameWithSuffix');
    $filename = $method($path);

    expect($filename)->toBe(__DIR__ . "/test_1.csv");
});

it('valid file fails', function () {
    $path  = __DIR__ . '/test.txt';

    $validateFile = getPrivateMethod(new CSVGenerator, 'validateFile');
    $result = $validateFile($path);

    expect($result)->toBeFalse();
});
