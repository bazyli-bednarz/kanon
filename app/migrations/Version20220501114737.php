<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501114737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE composers ADD edited_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE composers ADD CONSTRAINT FK_939EE6EBDD7B2EBC FOREIGN KEY (edited_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_939EE6EBDD7B2EBC ON composers (edited_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE composers DROP FOREIGN KEY FK_939EE6EBDD7B2EBC');
        $this->addSql('DROP INDEX IDX_939EE6EBDD7B2EBC ON composers');
        $this->addSql('ALTER TABLE composers DROP edited_by_id');
    }
}
