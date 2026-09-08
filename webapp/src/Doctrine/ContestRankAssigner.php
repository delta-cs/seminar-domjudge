<?php declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\Contest;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

/**
 * contest.ranknumber is a fork-only column: NOT NULL and UNIQUE, with no
 * database default, since a shared default would collide on the unique key.
 * The jury add, copy and import paths set it themselves, but a Contest built
 * directly -- as upstream's own tests do -- would be flushed without a rank
 * and fail on the NOT NULL constraint. Give those a rank at the end.
 *
 * This is a listener rather than a #[ORM\PrePersist] callback on the entity
 * because BaseController invokes those callbacks by hand with no arguments,
 * so a callback needing the event args would fatal there.
 */
#[AsDoctrineListener(event: Events::prePersist)]
class ContestRankAssigner
{
    public function __invoke(PrePersistEventArgs $args): void
    {
        $contest = $args->getObject();
        if (!$contest instanceof Contest || $contest->hasRank()) {
            return;
        }

        $entityManager = $args->getObjectManager();
        $maxRank = (int)$entityManager->createQueryBuilder()
            ->from(Contest::class, 'c')
            ->select('COALESCE(MAX(c.ranknumber), 0)')
            ->getQuery()
            ->getSingleScalarResult();

        // Contests already queued in this flush are not in the database yet,
        // so consider them too or a batch would hand out one rank twice.
        foreach ($entityManager->getUnitOfWork()->getScheduledEntityInsertions() as $queued) {
            if ($queued !== $contest && $queued instanceof Contest && $queued->hasRank()) {
                $maxRank = max($maxRank, $queued->getRank());
            }
        }

        $contest->setRank($maxRank + 1);
    }
}
