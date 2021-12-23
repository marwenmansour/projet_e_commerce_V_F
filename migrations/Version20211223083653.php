<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211223083653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, prix_totale DOUBLE PRECISION NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_fruits_legumes (panier_id INT NOT NULL, fruits_legumes_id INT NOT NULL, INDEX IDX_B717EE58F77D927C (panier_id), INDEX IDX_B717EE582C147EA9 (fruits_legumes_id), PRIMARY KEY(panier_id, fruits_legumes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE panier_fruits_legumes ADD CONSTRAINT FK_B717EE58F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_fruits_legumes ADD CONSTRAINT FK_B717EE582C147EA9 FOREIGN KEY (fruits_legumes_id) REFERENCES fruits_legumes (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE utlisateur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_fruits_legumes DROP FOREIGN KEY FK_B717EE58F77D927C');
        $this->addSql('CREATE TABLE utlisateur (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_fruits_legumes');
    }
}
