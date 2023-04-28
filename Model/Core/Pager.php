<?php 

class Model_Core_Pager
{
	protected $totalRecords = 0;
	protected $recordPerPage = 10;
	protected $currentPage = 0;
	protected $numberOfPages = 0;
	protected $start = 1;
	protected $previous = 0;
	protected $next = 0;
	protected $end = 0;
	protected $startLimit = 0;

	public function __construct()
	{
		
	}

	public function calculate()
	{
        //numberofpages
        if (($this->numberOfPages = ceil($this->getTotalRecords() / $this->getRecordPerPage())) == 0) {
            $this->currentPage = 0;
        };
        if ($this->getNumberOfPages() == 1 || ($this->getNumberOfPages() > 1 && $this->getCurrentPage() <= 0)) {
            $this->currentPage = 1;
        }
        if ($this->getCurrentPage() > $this->getNumberOfPages()) {
            $this->currentPage = $this->getNumberOfPages();
        }

        //start
        if (!$this->getNumberOfPages()) {
            $this->start = 0;
        }
        if ($this->getCurrentPage() == 1) {
            $this->start = 0;
        }

        //end
        $this->end = $this->getNumberOfPages();
        if ($this->getCurrentPage() == $this->getNumberOfPages()) {
            $this->end = 0;
        }

        //previous
        $this->previous = $this->getCurrentPage() - 1;
        if ($this->getCurrentPage() <= 1) {
            $this->previous = 0;    
        }
        
        //next
        $this->next = $this->getCurrentPage() + 1;
        if ($this->getCurrentPage() >= $this->getNumberOfPages()) {
            $this->next = 0;
        }

        //start limit
        if ($this->getCurrentPage() == 0) {
            $this->setCurrentPage(1);
        }
        $this->startLimit = ($this->getCurrentPage() - 1) * $this->getRecordPerPage(); 
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