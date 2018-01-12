<?php


namespace Cleargo\Integrationframeworks\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Cleargo\Integrationframeworks\Cron\RunWorkflow;

class TestExecuteSchedule extends Command
{

    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
        RunWorkflow $runWorkflow
    ) {
        $name = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);
        $output->writeln("TestExecuteSchedule " . $name);
        $runWorkflow->executePlan();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("cleargo_integrationframeworks:executeSchedule");
        $this->setDescription("test integaration");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }
}
