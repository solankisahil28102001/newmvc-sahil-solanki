<?php 

class Model_Core_Pager
{
	public $totalRecords = 0;
	public $recordPerPage = 10;
	public $currentPage = 0;
	public $numberOfPages = 0;
	public $start = 1;
	public $previous = 0;
	public $next = 0;
	public $end = 0;
	public $startLimit = 0;

	public function __construct($totalRecords, $currentPage)
	{
		$this->totalRecords = $totalRecords;
		if ($currentPage == 0) {
			$this->currentPage = 1;
		}
		else{
			$this->currentPage = $currentPage;
		}
	}

	public function calculate()
	{
		// calculate start
		if (($this->numberOfPages = ceil($this->totalRecords / $this->recordPerPage)) == 0) {
			$this->numberOfPages = 1;
		};
		$this->previous = $this->currentPage - 1;
		$this->next = ($this->currentPage == $this->numberOfPages) ? $this->currentPage : $this->currentPage + 1;
		$this->end = $this->numberOfPages;
		$this->startLimit = ($this->currentPage == 1) ? 1 : ($this->currentPage * $this->numberOfPages) - 9;
	}

    public function getTotalRecords()
    {
        return $this->totalRecords;
    }

    public function setTotalRecords($totalRecords)
    {
        $this->totalRecords = $totalRecords;
        return $this;
    }

    public function getRecordPerPage()
    {
        return $this->recordPerPage;
    }

    public function setRecordPerPage($recordPerPage)
    {
        $this->recordPerPage = $recordPerPage;
        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages($numberOfPages)
    {
        $this->numberOfPages = $numberOfPages;
        return $this;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    public function getPrevious()
    {
        return $this->previous;
    }

    public function setPrevious($previous)
    {
        $this->previous = $previous;
        return $this;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function setNext($next)
    {
        $this->next = $next;
        return $this;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    public function getStartLimit()
    {
        return $this->startLimit;
    }

    public function setStartLimit($startLimit)
    {
        $this->startLimit = $startLimit;
        return $this;
    }
}