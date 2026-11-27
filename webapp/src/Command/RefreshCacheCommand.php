<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Contest;
use App\Service\ScoreboardService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'domjudge:refresh-cache',
    description: 'Refreshes the scoreboard caches for all contests'
)]
class RefreshCacheCommand extends Command
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly ScoreboardService $scoreboardService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('contest', 'c', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Only refresh these contests (internal ID); repeat for more. Default: all contests.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $ids = $input->getOption('contest');
        $repository = $this->em->getRepository(Contest::class);
        $contests = empty($ids) ? $repository->findAll() : $repository->findBy(['cid' => $ids]);
        if (!empty($ids) && count($contests) !== count(array_unique($ids))) {
            $style->error('Unknown contest ID among: ' . implode(', ', $ids));
            return Command::FAILURE;
        }
        foreach ($contests as $contest) {
            $start = microtime(true);
            $this->scoreboardService->refreshCache($contest);
            $style->success(sprintf('Refreshed cache for contest %s in %.1f s.',
                $contest->getName(), microtime(true) - $start));
        }

        return Command::SUCCESS;
    }
}
