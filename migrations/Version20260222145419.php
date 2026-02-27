<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260222145419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vente (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vente_produit (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, vente_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_76AF70C77DC7170A (vente_id), INDEX IDX_76AF70C7F347EFB (produit_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE vente_produit ADD CONSTRAINT FK_76AF70C77DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE vente_produit ADD CONSTRAINT FK_76AF70C7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente_produit DROP FOREIGN KEY FK_76AF70C77DC7170A');
        $this->addSql('ALTER TABLE vente_produit DROP FOREIGN KEY FK_76AF70C7F347EFB');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE vente');
        $this->addSql('DROP TABLE vente_produit');
    }
}
