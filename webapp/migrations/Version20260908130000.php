<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * The fork has always awarded partial points on every contest: 8.3.2's
 * ScoreboardService summed Judging::getPointsScored() unconditionally, with no
 * notion of a contest "type". DOMjudge 9.0 introduced contest.scoreboard_type
 * and defaults it to 'pass-fail', which switches the fork's partial-points
 * scoring off wholesale.
 *
 * Map the fork's pre-9.0 semantics onto upstream's new flag: contests that hold
 * partial-points data become 'scoring', and new contests default to it. A
 * contest can still be switched to 'pass-fail' from the jury contest form to
 * get upstream's behaviour, which the fork could not express before.
 */
final class Version20260908130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Keep the fork's partial-points scoring by marking contests as 'scoring'";
    }

    public function up(Schema $schema): void
    {
        // Only contests that actually carry partial-points data become 'scoring'.
        // Contests judged before the fork grew the feature have points_scored = 0
        // on every judging, so scoring them by summed points would zero their
        // scoreboards; upstream's solved-count formula is right for those.
        $this->addSql("
            UPDATE contest c SET c.scoreboard_type = 'scoring'
            WHERE EXISTS (
                SELECT 1 FROM submission s
                JOIN judging j ON j.submitid = s.submitid
                WHERE s.cid = c.cid AND j.points_scored > 0
            )");
        $this->addSql("ALTER TABLE contest CHANGE scoreboard_type scoreboard_type VARCHAR(255) DEFAULT 'scoring' NOT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE contest CHANGE scoreboard_type scoreboard_type VARCHAR(255) DEFAULT 'pass-fail' NOT NULL");
        // Deliberately not reverting the rows: which contests were 'scoring'
        // before this migration ran is not recoverable.
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
