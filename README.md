# CSV  Generator

Generador de archivos formato csv

## Instalación
...

## Uso

Para utilizar esta libreria es muy simple, solo generamos la instancia del archivo y la utilizamos de la siguiente forma:

```php
use CSVGenerator\CSVGenerator;

// Especificamos la ruta de donde se generara el archivo con ext csv.
$file = __DIR__ . '/test.csv';

(new CSVGenerator)->create($file, ['id', 'name', 'company'])
    ->add([1, 'luis', 'company1'])
```

## Agregar datos

Puedes crear datos en lote o poco a poco dentro del flujo de tu aplicación

```php

$file = __DIR__ . '/test.csv';

$generator = new CSVGenerator($file);

// paso a paso
$generator->add([1, 'luis', 'company1']);
$generator->add([2, 'foo', 'company2']);

// en lote
$generator->add([
    [1, 'luis', 'company1'],
    [2, 'foo', 'company2'],
    [3, 'bar', 'company3'],
);

```

Tambien puedes crear un archivo con datos con la funcion `create()`
```php
$file = __DIR__ . '/test.csv';

(new CSVGenerator)->create($path, [ 
    [1, 'luis', 'company1'],
    // ...
]);
```

Si el archivo se encuentra duplicado la libreria va generar un archivo con suffix para evitar sobrescribir la data

```bash 
src/
├── test.csv
├── test_1.csv
└── test_2.csv
```

## API

#### `add`
```php
/**
 * Se encarga de agregar data a un archivo existente regresa
 * falso si ocurrio algun problema
 *
 * @param array $rows 
 * 
 * @return bool
 */

// rows puede ser un array unidimensional o bidi ej: [..] o [[...], [...]]
$obj->add(array $rows);
```

#### `create`
```php
/**
 * Se encarga de crear un archivo y agregar datos
 *
 * @param string $path
 * @param array $rows
 * @return self
 */

// rows puede ser un array unidimensional o bidi ej: [..] o [[...], [...]]
$obj->create(string $pathfile, array $rows);
```


## Test
Los test de estas librerias se encuentran programados en `pest` correr los test de este proyecto necesitaras realizar `composer install` y ejecutar el siguiente comando en consola

```bash
./vendor/bin/pest


PASS  Tests\Feature\CSVGeneratorTest
✓ it create file via constructor
✓ it create file via method create
✓ it create file when name is duplicated
✓ it create csv file with data
✓ it attempt add data to csv without file
✓ it generate correct csv format.
✓ it generate file in steps
✓ it generate file in steps with chaining functions

PASS  Tests\Unit\CSVGeneratorTest
✓ it add suffix to a name
✓ it generate filename with suffix
✓ it valid file fails

Tests:  11 passed
Time:   0.08s
```




