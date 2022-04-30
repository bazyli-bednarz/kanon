<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430204712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE canons (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, visibility TINYINT(1) NOT NULL, INDEX IDX_31F0E828F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composers (id INT AUTO_INCREMENT NOT NULL, period_id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, description VARCHAR(500) DEFAULT NULL, birth_year INT DEFAULT NULL, death_year INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, INDEX IDX_939EE6EBEC8B7ADE (period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(500) DEFAULT NULL, start_year INT DEFAULT NULL, end_year INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pieces (id INT AUTO_INCREMENT NOT NULL, composer_id INT NOT NULL, scale_id INT NOT NULL, author_id INT NOT NULL, edited_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(500) DEFAULT NULL, year INT DEFAULT NULL, link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, INDEX IDX_B92D74727A8D2620 (composer_id), INDEX IDX_B92D7472F73142C2 (scale_id), INDEX IDX_B92D7472F675F31B (author_id), INDEX IDX_B92D7472DD7B2EBC (edited_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pieces_tags (piece_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5044A01BC40FCFA8 (piece_id), INDEX IDX_5044A01BBAD26311 (tag_id), PRIMARY KEY(piece_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pieces_canons (piece_id INT NOT NULL, canon_id INT NOT NULL, INDEX IDX_7AAC6BDBC40FCFA8 (piece_id), INDEX IDX_7AAC6BDB7DE22DC4 (canon_id), PRIMARY KEY(piece_id, canon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scales (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E95E237E06 (name), UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE canons ADD CONSTRAINT FK_31F0E828F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE composers ADD CONSTRAINT FK_939EE6EBEC8B7ADE FOREIGN KEY (period_id) REFERENCES periods (id)');
        $this->addSql('ALTER TABLE pieces ADD CONSTRAINT FK_B92D74727A8D2620 FOREIGN KEY (composer_id) REFERENCES composers (id)');
        $this->addSql('ALTER TABLE pieces ADD CONSTRAINT FK_B92D7472F73142C2 FOREIGN KEY (scale_id) REFERENCES scales (id)');
        $this->addSql('ALTER TABLE pieces ADD CONSTRAINT FK_B92D7472F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pieces ADD CONSTRAINT FK_B92D7472DD7B2EBC FOREIGN KEY (edited_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pieces_tags ADD CONSTRAINT FK_5044A01BC40FCFA8 FOREIGN KEY (piece_id) REFERENCES pieces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pieces_tags ADD CONSTRAINT FK_5044A01BBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pieces_canons ADD CONSTRAINT FK_7AAC6BDBC40FCFA8 FOREIGN KEY (piece_id) REFERENCES pieces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pieces_canons ADD CONSTRAINT FK_7AAC6BDB7DE22DC4 FOREIGN KEY (canon_id) REFERENCES canons (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pieces_canons DROP FOREIGN KEY FK_7AAC6BDB7DE22DC4');
        $this->addSql('ALTER TABLE pieces DROP FOREIGN KEY FK_B92D74727A8D2620');
        $this->addSql('ALTER TABLE composers DROP FOREIGN KEY FK_939EE6EBEC8B7ADE');
        $this->addSql('ALTER TABLE pieces_tags DROP FOREIGN KEY FK_5044A01BC40FCFA8');
        $this->addSql('ALTER TABLE pieces_canons DROP FOREIGN KEY FK_7AAC6BDBC40FCFA8');
        $this->addSql('ALTER TABLE pieces DROP FOREIGN KEY FK_B92D7472F73142C2');
        $this->addSql('ALTER TABLE pieces_tags DROP FOREIGN KEY FK_5044A01BBAD26311');
        $this->addSql('ALTER TABLE canons DROP FOREIGN KEY FK_31F0E828F675F31B');
        $this->addSql('ALTER TABLE pieces DROP FOREIGN KEY FK_B92D7472F675F31B');
        $this->addSql('ALTER TABLE pieces DROP FOREIGN KEY FK_B92D7472DD7B2EBC');
        $this->addSql('DROP TABLE canons');
        $this->addSql('DROP TABLE composers');
        $this->addSql('DROP TABLE periods');
        $this->addSql('DROP TABLE pieces');
        $this->addSql('DROP TABLE pieces_tags');
        $this->addSql('DROP TABLE pieces_canons');
        $this->addSql('DROP TABLE scales');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
    }
}
