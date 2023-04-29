<?php 

class Model_Core_File_CSv
{
	protected $handler = null;
    protected $file = null;
    protected $fileName = null;
    protected $path = 'var';
    protected $header = [];
    protected $rows = [];

    public function read()
    {
        $this->open();

        while ($row = fgetcsv($this->getHandler(), 4096)) {
            
            if(!$this->header){
                $this->header = $row;
            }
            else{
                $this->rows[] = array_combine($this->header, $row);
            }
        }

        $this->close();
        return $this;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function open()
    {
        $this->handler = fopen($this->getPath(), "r");
        return $this;
    }

    public function close()
    {
        if($this->getHandler()){
            fclose($this->getHandler());
        }
    }


    public function setPath($subPath)
    {
        if($subPath){
            $this->path = Ccc::getBaseDir(DS . $this->path. DS . $subPath);
        }
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }
}