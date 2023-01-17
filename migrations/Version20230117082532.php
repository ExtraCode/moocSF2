<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117082532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, popularite INT NOT NULL, couleur VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeu ADD genre_id INT NOT NULL, DROP genre');

        $this->addSql('INSERT INTO genre(id,nom,popularite,couleur) VALUES (1,"Sans genre",0,NULL)');
        $this->addSql('UPDATE jeu SET genre_id = 1');

        $this->addSql('ALTER TABLE jeu ADD CONSTRAINT FK_82E48DB54296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_82E48DB54296D31F ON jeu (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu DROP FOREIGN KEY FK_82E48DB54296D31F');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP INDEX IDX_82E48DB54296D31F ON jeu');
        $this->addSql('ALTER TABLE jeu ADD genre VARCHAR(50) NOT NULL, DROP genre_id');
    }
}
