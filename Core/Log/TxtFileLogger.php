<?php

namespace Core\Log;

class TxtFileLogger implements LoggerInterface
{
	protected $filename;

	public function __construct($_filename)
	{
		$this->filename = $_filename;
	}

	public function write($_message)
	{
		\Core\Filesystem::append($this->filename, $_message);	
	}
}