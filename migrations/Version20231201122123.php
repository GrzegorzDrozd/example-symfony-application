<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201122123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(128) NOT NULL, password VARCHAR(96) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(96) NOT NULL, last_name VARCHAR(96) NOT NULL, UNIQUE INDEX UNIQ_70E4FA78F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vico (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vico_rating_comment (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, vico_id INT NOT NULL, content LONGTEXT NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CB94A0BC19EB6921 (client_id), INDEX IDX_CB94A0BC19F89217 (vico_id), UNIQUE INDEX UNIQ_CB94A0BC19F8921719EB6921 (vico_id, client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vico_rating_score (id INT AUTO_INCREMENT NOT NULL, vico_id INT NOT NULL, client_id INT NOT NULL, name VARCHAR(255) NOT NULL, value SMALLINT NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9DFF039C19F89217 (vico_id), INDEX IDX_9DFF039C19EB6921 (client_id), UNIQUE INDEX UNIQ_9DFF039C19F8921719EB69215E237E06 (vico_id, client_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vico_rating_comment ADD CONSTRAINT FK_CB94A0BC19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE vico_rating_comment ADD CONSTRAINT FK_CB94A0BC19F89217 FOREIGN KEY (vico_id) REFERENCES vico (id)');
        $this->addSql('ALTER TABLE vico_rating_score ADD CONSTRAINT FK_9DFF039C19F89217 FOREIGN KEY (vico_id) REFERENCES vico (id)');
        $this->addSql('ALTER TABLE vico_rating_score ADD CONSTRAINT FK_9DFF039C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vico_rating_comment DROP FOREIGN KEY FK_CB94A0BC19EB6921');
        $this->addSql('ALTER TABLE vico_rating_comment DROP FOREIGN KEY FK_CB94A0BC19F89217');
        $this->addSql('ALTER TABLE vico_rating_score DROP FOREIGN KEY FK_9DFF039C19F89217');
        $this->addSql('ALTER TABLE vico_rating_score DROP FOREIGN KEY FK_9DFF039C19EB6921');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE vico');
        $this->addSql('DROP TABLE vico_rating_comment');
        $this->addSql('DROP TABLE vico_rating_score');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
