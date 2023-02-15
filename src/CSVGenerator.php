<?php

/**
 * Libreria para exportar informacion en csv.
 *
 * (c) Neubox Internet 2023 <luisromero@neubox.net>
 */

namespace CSVGenerator;

/**
 * CSVGenerator
 *
 * Clase principal para generación de archivos.
 */
final class CSVGenerator
{
    /**
     * Pathfile del archivo
     *
     * @var string
     */
    protected $pathFile = '';

    /**
     * Mensaje de error
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * Constructor, puedes crear un archivo al
     * inicializar la instancia
     *
     * @param string $path
     */
    public function __construct($path = '')
    {
        if (! empty($path) && $this->validateFile($path)) {
            if (! file_exists($path)) {
                if (! $this->createFile($path)) {
                    echo $this->errorMessage;
                }
            } else {
                $this->pathFile = $path;
            }
        }
    }

    public function setFile(string $path): self
    {
        $this->pathFile = $path;
        return $this;
    }

    public function getFileInfo(): array
    {
        return $this->getCsvInfo($this->pathFile);
    }

    /**
     * Se encarga de crear un archivo y agregar datos
     *
     * @param string $path
     * @param array $rows
     * @return self
     */
    public function create(string $path, array $rows = []): self
    {
        if ($this->validateFile($path)) {
            $this->createFile($path);
        }

        $this->add($rows);

        return $this;
    }

    /**
     * Se encarga de agregar data a un archivo existente
     *
     * @param array $rows
     * @return bool
     */
    public function add(array $rows): bool
    {
        if (empty($rows)) {
            return true;
        }

        if (! $this->validateFile()) {
            return false;
        }

        if (! $file = fopen($this->pathFile, 'a')) {
            $this->errorMessage = "El archivo {$this->pathFile} no existe.";

            return false;
        }

        if (empty(array_filter($rows, 'is_array'))) { // is unidimensional?
            $rows = [$rows];
        }

        foreach ($rows as $row) {
            if (! fputcsv($file, $row)) {
                $this->errorMessage = "Error al escribir en el fichero: {$this->pathFile}.";

                return false;
            }
        }

        if (! fclose($file)) {
            $this->errorMessage = "Error al cerrar el fichero: {$this->pathFile}.";

            return false;
        }

        return true;
    }

    /**
     * Regresa un posible mensaje de error
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->errorMessage;
    }

    /**
     * Se encarga de crear nuevos archivos
     *
     * @param string $path
     * @return bool
     */
    private function createFile(string $path): bool
    {
        try {
            if (file_exists($path)) {
                $path = $this->getFilenameWithSuffix($path);
            }

            $this->generateFile($path);
        } catch (\Throwable $th) {
            $this->errorMessage = $th->getMessage();

            return false;
        }

        $this->pathFile = $path;

        return true;
    }

    /**
     * Genera un path de archivo con sufijo incremental
     *
     * @param string $path
     * @return string
     */
    private function getFilenameWithSuffix(string $path): string
    {
        $info = $this->getCsvInfo($path);
        $filename = $this->addSuffix($info['basename']);
        $new_path = "{$info['path']}/{$filename}.{$info['extension']}";

        if (file_exists($new_path)) {
            $new_path = $this->getFilenameWithSuffix($new_path);
        }

        return $new_path;
    }

    private function getCsvInfo(string $path): array
    {
        $file = new \SplFileInfo($path);
        $basename = $file->getBasename('.csv');
        $extension = $file->getExtension();

        return [
            'filename' => "{$basename}.{$extension}",
            'basename' => $basename,
            'extension' => $extension,
            'path' => $file->getPath()
        ];
    }

    /**
     * Agrega un sufijo incremental a un string
     *
     * @param $name
     * @return string
     */
    private function addSuffix(string $name): string
    {
        $match = preg_match('/_\d+$/', $name, $matches);

        if ($match === false) {
            throw new \Exception('Nombre de archivo no válido.');
        }

        if ($match === 0) {
            $name = "{$name}_1";
        }

        if ($match === 1) {
            $suffix = (int) str_replace('_', '', $matches[0]);
            $suffix += 1;
            $name = preg_replace('/_\d+$/', "_{$suffix}", $name);
        }

        return $name;
    }

    /**
     * Genera un nuevo archivo.
     *
     * @param $path
     * @return void
     */
    private function generateFile(string $path): void
    {
        if (! fopen($path, 'a')) {
            throw new \Exception('Error al crear el archivo.');
        }
    }

    /**
     * Válida la extensión del archivo.
     *
     * @param $path
     * @return bool
     */
    private function validateFile(string $path = ''): bool
    {
        $path = empty($path) ? $this->pathFile : $path;

        $valid = ! (empty($path) || ! preg_match('/\.csv$/', $path));

        if (! $valid) {
            $this->errorMessage = 'Debe proporcionar un archivo existente o inexistente con extensión .csv';
        }

        return $valid;
    }
}
