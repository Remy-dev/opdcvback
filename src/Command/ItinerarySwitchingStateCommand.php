<?php

namespace App\Command;

use App\Entity\Itinerary;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ItinerarySwitchingStateCommand extends Command
{
    protected static $defaultName = 'app:ItinerarySwitchingState';
    protected $manager;
    protected $logger;

    public function __construct(EntityManagerInterface $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Switch state of itineraries to archived state when date of departure has passed');
          //  ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description');
            /*->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ; */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $itineraryRepository = $this->manager->getRepository(Itinerary::class);
        $itineraries = $itineraryRepository->findAll();

            foreach ($itineraries as $itinerary)
            {
                if ('active' === $itinerary->getState())
                {
                    $nowTimestamp = time();
                    $hour = date($itinerary->getDateDeparture()->format('H'));
                    $day = date($itinerary->getDateDeparture()->format('j'));
                    $month = date($itinerary->getDateDeparture()->format('m'));
                    $year = date($itinerary->getDateDeparture()->format('Y'));

                    $itineraryDepatureTimeStamp = mktime($hour, 0, 0, $month, $day, $year);


                    if ($itineraryDepatureTimeStamp < $nowTimestamp)
                    {
                        $itinerary->setState('archived');

                        $io->success('Itineraries have been archived ');
                        continue;

                    }
                    else
                    {
                        continue;
                    }
                }
                // comparaison de temps en utilisant le timestamp

            }
            $this->manager->flush();
            return Command::SUCCESS;

    }
}
