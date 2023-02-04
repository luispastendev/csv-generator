<?php


class CSVGenerator 
{
	
	protected $file = '';
	protected $columns;
	protected $rows;
	
	public function __construct($file = "")
	{
		$this->file = $file;

		if (! empty($file)) {
			$this->checkFile();
			$this->generateFile($this->file);
		}
	}
	
	public function add(array $data)
	{
		$this->checkFile();
		
		$file = @fopen($this->file, 'a');
		
		if (! $file) {
			echo "El archivo {$this->file} no existe.";
		}

		if (empty(array_filter($data, 'is_array'))) { // is unidimensional?
			$data = [$data];
		}
	
		foreach ($data as $row) {
			fputcsv($file, $row);
		}
		
		fclose($file);
		
		return $this;
	}

	
	public function create(string $file, ...$data): self
	{
		$this->file = $file;
		$this->generateFile($file);
		
		if (! empty($data)) {
		
			[$rows, $columns] = array_pad($data, 2, []);
		
			$this->pushData($rows, $columns);
		}
		
		return $this;
	}
	
	
	private function generateFile(string $file, $suffix = 0) 
	{
		
		if (! file_exists($file)) {
			
			if (! @fopen($file, "a")) {
				echo "No se pudo crear el archivo, verifique el path";
				exit;
			}
			
			$this->file = $file;
				
			return $file;
			
		} else {
		
			$file      = new SplFileInfo($file);
			$filename  = $file->getBasename('.csv');
			$extension = $file->getExtension();
			$path      = $file->getPath();
			$suffix += 1;
			
			$filename = preg_replace('/(_\d+)?$/', "_{$suffix}", $filename, 1);		
		
			return $this->generateFile("{$path}/{$filename}.{$extension}", $suffix);
		}
	}
	
	
	private function checkFile(): bool
	{
		if (empty($this->file) || !preg_match('/\.csv$/', $this->file)) {
			echo "Debe proporcionar un archivo existente o inexistente con extensión .csv";
			exit;
		}
		return true;
	}
	
	private function pushData(array $rows = [], array $columns = [])
	{
		if (!empty($rows)) {
			$this->add($rows);
		}
		
		if (!empty($columns)) {
			$this->addColumns($columns);
		}
	}	
}