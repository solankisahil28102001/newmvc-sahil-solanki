<?php 

class Model_Core_File_Upload
{
	protected $file = null;
	protected $fileName = null;
	protected $path = 'var';
	protected $extension = ['xlsx','csv'];

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($name)
    {
        if (!array_key_exists($name, $_FILES)) {
            return false;
        }

        $this->file = $_FILES[$name];
        if (!$fileName = $this->getFileName()) {
            $this->setFileName($this->file['name']);
        }

        move_uploaded_file($this->file['tmp_name'], $this->getPath());
        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($subPath)
    {
        if ($subPath) {
            $this->path = Ccc::getBaseDir(DS .$this->path. DS .$subPath);
        }
        return $this;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }
}