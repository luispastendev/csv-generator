<?php

use CSVGenerator\CSVGenerator;

it('create file via constructor', function () {
    $path = __DIR__ . '/test.csv';
    new CSVGenerator($path);

    expect($path)->toBeFile();
    expect($path)->toBeReadableFile();
    expect($path)->toBeWritableFile();

    cleanDummyFiles($path);
});

it('create file via method create', function () {
    $path = __DIR__ . '/test.csv';
    (new CSVGenerator)->create($path);

    expect($path)->toBeFile();
    expect($path)->toBeReadableFile();
    expect($path)->toBeWritableFile();

    cleanDummyFiles($path);
});

it('create file when name is duplicated', function () {

    $original   = __DIR__ . '/test.csv';
    $duplicated = __DIR__ . '/test_1.csv';
    (new CSVGenerator)->create($original);
    (new CSVGenerator)->create($original);

    expect($original)
        ->toBeFile()
        ->and($duplicated)
        ->toBeFile();

    cleanDummyFiles([$original, $duplicated]);
});

it('create csv file with data', function () {
    $path = __DIR__ . '/test.csv';

    (new CSVGenerator)->create($path)->add(getDummyData());

    expect($path)
        ->toBeFile();

    cleanDummyFiles($path);
});

it('attempt add data to csv without file', function () {
    $path = __DIR__ . '/test.csv';

    (new CSVGenerator)->add(getDummyData());

    expect($path)
        ->not
        ->toBeFile();
});

it('generate correct csv format.', function () {
    $path = __DIR__ . '/test.csv';

    (new CSVGenerator)->create($path, getDummyData());
    $content = getFileContent($path);

    expect($content)->toEqual(getDummyData());

    cleanDummyFiles($path);
});


it('generate file in steps', function () {
    $path = __DIR__ . '/test.csv';

    $chunks = getDummyData();

    $csv_generator = (new CSVGenerator)->create($path, $chunks[0]);
    $csv_generator->add($chunks[1]);
    $csv_generator->add($chunks[2]);
    $csv_generator->add($chunks[3]);

    $content = getFileContent($path);
    expect($content)->toEqual(getDummyData());

    cleanDummyFiles($path);
});


it('generate file in steps with chaining functions', function () {
    $path = __DIR__ . '/test.csv';

    $data = getDummyData();
    (new CSVGenerator)->create($path)->add($data);
    $content = getFileContent($path);

    expect($content)->toEqual(getDummyData());

    cleanDummyFiles($path);
});

it('write to an existing file', function () {
    $path    = __DIR__ . '/dummy.csv';
    $columns = ['name', 'age'];
    $rows    = [['foo',10], ['bar',20]];

    createDummyFile($path, $columns);

    (new CSVGenerator)->setFile($path)->add($rows);

    $content = getFileContent($path);
    array_unshift($rows, $columns);

    expect($content)->toMatchArray($rows);

    cleanDummyFiles($path);
});

it('get file name', function () {
    $path = __DIR__ . '/dummy.csv';

    createDummyFile($path, getDummyData());

    $generator = new CSVGenerator;
    $generator->setFile($path)->add([10, 'php', 'fff']);

    $info = $generator->getFileInfo();
    expect($info['filename'])->toBe('dummy.csv');

    cleanDummyFiles($path);
});
