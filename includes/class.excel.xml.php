<?php
class ExcelXML{
	private $docFileName;
	private $docFilePath;
	private $fileHandle;

	function ExcelXML($fileName = 'file.xml',$filepath = ''){
  	$this->docFileName = $fileName;
		$this->docFilePath = $filepath;
  }
	private function openFile(){
		//$this->fileHandle = fopen($this->docFilePath . $this->docFileName, 'a+');
		if(!file_exists($this->docFilePath . $this->docFileName)) {
    die("File not found");
  } else {
    $this->fileHandle = fopen($this->docFilePath . $this->docFileName, 'a+');   // Also function executions errors are handle somehow
  }	
		if (!$this->fileHandle){
			die('<br/>No es posible abrir el archivo "'.$this->docFilePath . $this->docFileName.'" para escritura');
		}
	}
	public function flushFile(){
		if ($this->fileHandle){
			fflush($this->fileHandle);
		}
	}
	private function write($xml){
		if ($this->fileHandle){
			fwrite($this->fileHandle,$xml);
		}
	}
	private function closeFile(){
		if ($this->fileHandle){
			fclose($this->fileHandle);
		}
	}

  public function writeHeader($docTitle='GestOT'){
		$this->openFile();
		$docCreated = date('Y-m-d').'T'.date('H:i:s').'Z';
		$this->write("<?xml version=\"1.0\"?>\n<?mso-application progid=\"Excel.Sheet\"?>\n");
		$this->write("<Workbook\n\txmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n\txmlns:o=\"urn:schemas-microsoft-com:office:office\"\n\txmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n\txmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n\txmlns:html=\"http://www.w3.org/TR/REC-html40\">\n");
		$this->write("<DocumentProperties xmlns=\"urn:schemas-microsoft-com:office:office\">\n\t<Title>$docTitle</Title>\n\t<Author>GestOT Web</Author>\n\t<Created>$docCreated</Created>\n\t<Manager>GestOT</Manager>\n\t<Company>Movistar</Company>\n\t<Version>11.9999</Version>\n</DocumentProperties>\n");
		$this->write("<ExcelWorkbook xmlns=\"urn:schemas-microsoft-com:office:excel\" />\n");
		$this->write("<Styles>\n\t<Style ss:ID=\"H\">\n\t\t<Font ss:Color=\"#FFFFFF\" />\n\t\t<Interior ss:Color=\"#005177\" ss:Pattern=\"Solid\" />\n\t\t<NumberFormat />\n\t\t<Protection/>\n\t</Style>\n</Styles>\n");
		$this->flushFile();
  }
  public function openSheet($sheetName){
		$this->write("<Worksheet ss:Name=\"$sheetName\">\n\t<Table>\n");
	}
  public function closeSheet(){
		$this->write("\t</Table>\n</Worksheet>\n");
		$this->flushFile();
	}
  public function openRow($row){
		$this->write("\t\t<Row>\n");
	}
  public function closeRow(){
		$this->write("\t\t</Row>\n");
	}
  public function writeCell($type,$col,$data,$style = null){
		$data = htmlspecialchars($data);
		$data = str_replace("\r\n",'&#10;',$data);
		$data = str_replace("\n",'&#10;',$data);
		$cell = isset($data)&&strlen($data)>0?"<Data ss:Type=\"$type\">$data</Data>":"";
		if($style != null){
			$this->write("\t\t\t<Cell ss:StyleID=\"$style\">$cell</Cell>\n");
		} else {
			$this->write("\t\t\t<Cell>$cell</Cell>\n");
		}
	}
	public function writeString($col,$data,$style = null){
		$this->writeCell('String',$col,$data,$style);
	}
	public function writeNumber($col,$data,$style = null){
		if (!is_numeric($data)){
			$this->writeString($col,$data,$style);
		} else {
			$this->writeCell('Number',$col,$data,$style);
		}
	}
  public function writeFooter(){
		$this->write('</Workbook>');
		$this->closeFile();
	}
}
?>
