<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260222150400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente ADD user_id INT NOT NULL, CHANGE date date_vente DATETIME NOT NULL');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_888A2A4CA76ED395 ON vente (user_id)');
        $this->addSql('ALTER TABLE vente_produit ADD CONSTRAINT FK_76AF70C77DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE vente_produit ADD CONSTRAINT FK_76AF70C7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4CA76ED395');
        $this->addSql('DROP INDEX IDX_888A2A4CA76ED395 ON vente');
        $this->addSql('ALTER TABLE vente DROP user_id, CHANGE date_vente date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE vente_produit DROP FOREIGN KEY FK_76AF70C77DC7170A');
        $this->addSql('ALTER TABLE vente_produit DROP FOREIGN KEY FK_76AF70C7F347EFB');
    }
}
