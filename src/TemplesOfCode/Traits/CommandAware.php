<?php

namespace TemplesOfCode\Traits;

use Symfony\Component\Console\Output\OutputInterface;

trait CommandAware
{
  protected $commandOutput = array();
  protected $returnStatus = null;
  
  /**
   * @param string $cmd
   * @param bool $checkOutputToo
   * @return bool
   */
  protected function runCommand($cmd, $checkOutputToo = false)
  {
    $this->commandOutput = array();
    $this->returnStatus = null;

    /**
     * Note: other mechanisms can be used here as well - backticks for example.
     * Fire!
     */
    exec($cmd, $this->commandOutput, $this->returnStatus);

    if ($checkOutputToo) {
      $this->returnStatus = $returnStatus || empty($this->commandOutput);
    }

    if ($this->returnStatus) {
      return false;
    }

    return true;
  }
    
  /**
   * Execute and buffers command result to print it
   *
   * @param $command
   * @param OutputInterface $output
   * @return bool
   */
  private function executeWithOutput($command, OutputInterface $output)
  {
    $this->commandOutput = array();
    $this->returnStatus = null;

    static $bufferLength = 1024;
        
    $fp = popen($command, "r");
    if (!$fp) {
      return false;
    }
    
    while (!feof($fp)) {
      /**
       * @var string $incomingOutput
       */
      $incomingOutput = fread($fp, $bufferLength);

      $this->printIncomingOutput($incomingOutput, $output);
      flush();
    }

    /**
     * @var int $returnStatus
     */
    $this->returnStatus = pclose($fp);

    return $this->returnStatus > 0 ? false : true;        
  }

 /**
   * @param string $incomingOutput
   * @param OutputInterface $output
   */
  private function printIncomingOutput($incomingOutput, OutputInterface $output)
  {
    /**
     * Temporary holder for inter-call printing
     *
     * @var string $lastLine
     */
    static $lastLine = null;

    /**
     * @var string[]
     */
    $lines = explode("\n", $incomingOutput);

    if (!empty($lastLine)) {
      /**
       * If there was an incomplete line in the
       * previous call, complete it.
       */
      $lines[0] = $lastLine.$lines[0];
      $lastLine = null;
    }

    if (substr($incomingOutput,-1) != "\n") {
      /**
       * Last line is incomplete.
       * Save it to temp placeholder.
       */
      $lastLine = array_pop($lines);
    }

    foreach ($lines as $line) {
      /**
       * @var string $line
       */
      $output->writeln($line);
    }
  }
  
  /**
   * @return array
   */
  public function getCommandOutput()
  {
    return $this->commandOutput;
  }

  /**
   * @return int|null
   */
  public function getCommandReturnStatus()
  {
    return $this->returnStatus;
  }
}
