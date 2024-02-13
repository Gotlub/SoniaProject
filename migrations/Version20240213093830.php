<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213093830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD mail VARCHAR(255) DEFAULT NULL, ADD tel VARCHAR(255) DEFAULT NULL, ADD adresse_client VARCHAR(1000) DEFAULT NULL, ADD cp_client VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD ef_etudes TINYINT(1) DEFAULT NULL, ADD edn TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP mail, DROP tel, DROP adresse_client, DROP cp_client');
        $this->addSql('ALTER TABLE rendez_vous DROP ef_etudes, DROP edn');
    }
}
