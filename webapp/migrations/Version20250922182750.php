<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250922182750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cascade delete from judgetask to judging_run to fix contest deletion';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE judging_run DROP FOREIGN KEY FK_29A6E6E13CBA64F2');
        $this->addSql('ALTER TABLE judging_run ADD CONSTRAINT FK_29A6E6E13CBA64F2 FOREIGN KEY (judgetaskid) REFERENCES judgetask (judgetaskid) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE judging_run DROP FOREIGN KEY FK_29A6E6E13CBA64F2');
        $this->addSql('ALTER TABLE judging_run ADD CONSTRAINT FK_29A6E6E13CBA64F2 FOREIGN KEY (judgetaskid) REFERENCES judgetask (judgetaskid) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
