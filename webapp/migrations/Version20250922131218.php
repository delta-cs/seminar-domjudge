<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250922131218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds default file for the online editor';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE language ADD default_editor_template_filename VARCHAR(255) DEFAULT NULL COMMENT \'Editor template filename for this language\', ADD default_editor_template_content LONGTEXT DEFAULT NULL COMMENT \'Editor template for this language\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE language DROP default_editor_template_filename, DROP default_editor_template_content');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
