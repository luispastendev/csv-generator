# CSV  Generator

Generador de archivos formato csv

## Instalación

Puedes realizarlo por composer mediante el comando:

```bash
composer require luispastendev/csv-generator
```

## Uso

Para utilizar esta libreria es muy simple, solo generamos la instancia del archivo y la utilizamos de la siguiente forma:

```php
use CSVGenerator\CSVGenerator;

// Especificamos la ruta de donde se generara el archivo con ext csv.
// si el archivo ya existe se creara uno nuevo
$file = __DIR__ . '/test.csv';

// clasico
$csv_generator = new CSVGenerator;
$csv_generator->create($file, ['id', 'name', 'company']);
$csv_generator->add([1, 'luis', 'company1']);
$csv_generator->add([2, 'foo', 'company2']);

// chaining functions
(new CSVGenerator)->create($file, ['id', 'name', 'company'])
    ->add([
        [1, 'luis', 'company1'],
        [2, 'foo', 'company2']
    ]);
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
    [3, 'bar', 'company3']
);
```

Tambien puedes crear un archivo con datos con la funcion `create()`
```php
$path = __DIR__ . '/test.csv';

(new CSVGenerator)->create($path, [ 
    [1, 'luis', 'company1'],
    // ...
]);
```

Si el archivo se encuentra duplicado la libreria va generar un nuevo archivo con suffix

```bash 
src/
├── test.csv
├── test_1.csv
└── test_2.csv
```

## Trabajar con archivos existentes

Puedes trabajar con archivos que ya existen para agregar contenido nuevo
```php
$path = __DIR__ . '/ya-existo.csv';

$generator = new CSVGenerator($path);
$generator->add([1,'foo','bar']);

// o tambien ... 

(new CSVGenerator)->setFile($path)->add([
    [1,'foo','bar'],
    //...
]);
```
Si tu lo necesitas puedes obtener información del archivo existente o generado
```php
$generator->setFile($path)->add([10, 'php', 'fff']);
$info = $generator->getFileInfo();

// [
//    'filename'  => 'ya-existo.csv',
//    'basename'  => 'ya-existo',
//    'extension' => 'csv',
//    'path'      => /path/dir/
// ]
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

#### `setFile`
```php
/**
 * Establece un archivo para trabajar sobre el.
 *
 * @param string $path
 * @return self
 */

$obj->setFile(string $path);
```

#### `getFileInfo`
```php
/**
 * Regresa información del archivo nuevo o existente.
 *
 * @return array
 */

$obj->getFileInfo(string $path);
```

## Test
Los test de estas librerias se encuentran programados en `pest` correr los test de este proyecto necesitaras realizar `composer install` y ejecutar el siguiente comando en consola

```
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
✓ it write to an existing file
✓ it get file name

PASS  Tests\Unit\CSVGeneratorTest
✓ it add suffix to a name
✓ it generate filename with suffix
✓ it valid file fails

Tests:  13 passed
```




