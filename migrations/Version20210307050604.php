<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307050604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type SMALLINT DEFAULT NULL, possible_value LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, change_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_feature (genre_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_2452E69E4296D31F (genre_id), INDEX IDX_2452E69E60E4B879 (feature_id), PRIMARY KEY(genre_id, feature_id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB');
        $this->addSql('CREATE TABLE value_of_feature (id INT AUTO_INCREMENT NOT NULL, feature_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, value LONGTEXT NOT NULL, INDEX IDX_C0E5006B60E4B879 (feature_id), INDEX IDX_C0E5006B4296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genre_feature ADD CONSTRAINT FK_2452E69E4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_feature ADD CONSTRAINT FK_2452E69E60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE value_of_feature ADD CONSTRAINT FK_C0E5006B60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE value_of_feature ADD CONSTRAINT FK_C0E5006B4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre_feature DROP FOREIGN KEY FK_2452E69E60E4B879');
        $this->addSql('ALTER TABLE value_of_feature DROP FOREIGN KEY FK_C0E5006B60E4B879');
        $this->addSql('ALTER TABLE genre_feature DROP FOREIGN KEY FK_2452E69E4296D31F');
        $this->addSql('ALTER TABLE value_of_feature DROP FOREIGN KEY FK_C0E5006B4296D31F');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_feature');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE value_of_feature');
    }
}
