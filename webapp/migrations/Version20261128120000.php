<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Remember the interface language a user picked (#149), so it follows them to other devices.
 */
final class Version20261128120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user.locale for the chosen interface language';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE user ADD locale VARCHAR(5) DEFAULT NULL COMMENT 'Interface language chosen by the user, null for the default' AFTER name");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP locale');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
