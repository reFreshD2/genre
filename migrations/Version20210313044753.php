<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313044753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE possible_value (id INT AUTO_INCREMENT NOT NULL, feature_id INT DEFAULT NULL, value LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_163E73A60E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE possible_value ADD CONSTRAINT FK_163E73A60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE feature DROP possible_value');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE possible_value');
        $this->addSql('ALTER TABLE feature ADD possible_value LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
    }
}
