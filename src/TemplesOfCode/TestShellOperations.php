<?php

namespace TemplesofCode;

use \TemplesOfCode\Traits\CommandAware;
use Symfony\Component\Console\Output\OutputInterface;

class TestShellOperations
{
	use CommandAware;

	private $command1 = 'echo "hello shell"';
	private $command2 = 'uname -a';

	/**
	 * @var OutputInterface 
	 */
	private $output;

	public function __construct(OutputInterface $o)
	{
		$this->output = $o;
	}

	public function run()
	{
		$this->output->writeln('Executing Command: '.$this->command1);
		$success = $this->runCommand($this->command1);
		if (!$success) {
			$this->output->writeln('Command 1 failed');
		}

		$this->output->writeln("Command return status: ".$this->getCommandReturnStatus());

		$this->output->writeln('Executing Command: '.$this->command2);
		$success = $this->executeWithOutput($this->command2, $this->output);
		if (!$success) {
			$this->output->writeln('Command 2 failed');
		}
		$this->output->writeln("Command return status: ".$this->getCommandReturnStatus());

	}
	
}
