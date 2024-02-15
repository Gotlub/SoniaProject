<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215121946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, dernier_rdv_id INT DEFAULT NULL, prochaine_visite DATE DEFAULT NULL, adresse VARCHAR(2000) NOT NULL, cp VARCHAR(255) NOT NULL, commune VARCHAR(1000) NOT NULL, section_cadastrale VARCHAR(255) DEFAULT NULL, ancienne_adresse VARCHAR(2000) DEFAULT NULL, UNIQUE INDEX UNIQ_C35F08166027C82A (dernier_rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, dernier_rdv_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse_facturation VARCHAR(2000) DEFAULT NULL, cp_facturation VARCHAR(255) DEFAULT NULL, commune_facturation VARCHAR(500) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, adresse_client VARCHAR(1000) DEFAULT NULL, cp_client VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C74404556027C82A (dernier_rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, adresse_id INT DEFAULT NULL, status VARCHAR(500) DEFAULT NULL, facturation VARCHAR(500) DEFAULT NULL, date_facturation VARCHAR(255) DEFAULT NULL, commentaire VARCHAR(1000) DEFAULT NULL, status_dossier VARCHAR(2000) DEFAULT NULL, type_controle VARCHAR(2000) DEFAULT NULL, num_dossier VARCHAR(255) DEFAULT NULL, date_controle DATE DEFAULT NULL, type_traitement VARCHAR(1000) DEFAULT NULL, type_installation VARCHAR(1000) DEFAULT NULL, rejet_inf VARCHAR(1000) DEFAULT NULL, conformite VARCHAR(500) DEFAULT NULL, impact VARCHAR(500) DEFAULT NULL, type_rpqs VARCHAR(255) DEFAULT NULL, ef_etudes TINYINT(1) DEFAULT NULL, edn TINYINT(1) DEFAULT NULL, INDEX IDX_65E8AA0A19EB6921 (client_id), INDEX IDX_65E8AA0A4DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F08166027C82A FOREIGN KEY (dernier_rdv_id) REFERENCES rendez_vous (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404556027C82A FOREIGN KEY (dernier_rdv_id) REFERENCES rendez_vous (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F08166027C82A');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404556027C82A');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A19EB6921');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A4DE7DC5C');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
