<?php 

class Block_Core_Layout extends Block_Core_Template
{

	public function __construct()
	{
		parent::__construct();
		$this->setTemplate("core/layout/3columns.phtml");
		$this->prepareChildren();
	}

	public function prepareChildren()
	{
		$header = $this->createBlock('Html_Header');
		$header->setLayout($this);
		$this->addChild('header', $header);

		$head = $this->createBlock('Html_Head');
		$head->setLayout($this);
		$this->addChild('head', $head);
	
		$message = $this->createBlock('Html_Message');
		$message->setLayout($this);
		$this->addChild('message', $message);
		
		$left = $this->createBlock('Html_Left');
		$left->setLayout($this);
		$this->addChild('left', $left);
		
		$content = $this->createBlock('Html_Content');
		$content->setLayout($this);
		$this->addChild('content', $content);
		
		$right = $this->createBlock('Html_Right');
		$right->setLayout($this);
		$this->addChild('right', $right);
		
		$footer = $this->createBlock('Html_Footer');
		$footer->setLayout($this);
		$this->addChild('footer', $footer);
	}

	public function createBlock($className)
	{
		$className = "Block_".$className;
		$block = new $className();
		$block->setLayout($this);
		return $block;
	}

}

