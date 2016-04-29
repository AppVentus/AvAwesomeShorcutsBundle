<?php

namespace AppVentus\Awesome\ShortcutsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A base abstract command that provides shortcuts to some useful tools for the project.
 *
 * @codeCoverageIgnore
 */
abstract class BaseCommand extends ContainerAwareCommand
{
    private $formatter = null;

    /**
     * Holds our instance of the EntityManager.
     *
     * @var
     */
    private $em;

    /**
     * Holds our instance of the output.
     *
     * @var
     */
    protected $output;

    /**
     * Display message in the console.
     *
     * @param OutputInterface $output  Le flux de sortie
     * @param string          $message Le message à afficher
     **/
    protected function writeBlock(OutputInterface $output, $message)
    {
        if (null == $this->formatter) {
            $this->formatter = new FormatterHelper();
        }

        $output->writeln($this->formatter->formatBlock(
            $message,
            'bg=blue;fg=white',
            true
        ));
    }

    /**
     * Get the entity manager.
     *
     * @param string $managerName Nom du manager
     *
     * @return EntityManager le manager
     **/
    protected function getEntityManager($managerName = null)
    {
        if (null == $this->em) {
            $this->em = $this->getContainer()->get('doctrine')->getManager($managerName);
        }

        return $this->em;
    }

    /**
     * Get the logger.
     *
     * @return Logger The logger
     */
    protected function getLogger()
    {
        return $this->getContainer()->get('logger');
    }

    /**
     * Get service.
     *
     * @return service
     */
    protected function get($service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * Write the error to the log and the output.
     *
     * @param string $message
     */
    public function writeError($message)
    {
        //log in output
        if ($this->output !== null) {
            $this->output->write($message."\n");
        }
        //log in file
        $this->getLogger()->error($message);
    }

    /**
     * Write the message to the log and the output.
     *
     * @param string $message
     */
    public function write($message)
    {
        //log in output
        if ($this->output !== null) {
            $this->output->write($message."\n");
        }
        //log in file
        $this->getLogger()->info($message);
    }
}
