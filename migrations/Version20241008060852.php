<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008060852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE borrow (id INT AUTO_INCREMENT NOT NULL, borrower_id INT NOT NULL, objet_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_55DBA8B011CE312B (borrower_id), INDEX IDX_55DBA8B0F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, points INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_46CD4C387E3C61F9 (owner_id), INDEX IDX_46CD4C3812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, cumul_points INT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B011CE312B FOREIGN KEY (borrower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B0F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C387E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C3812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B011CE312B');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B0F520CF5A');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C387E3C61F9');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C3812469DE2');
        $this->addSql('DROP TABLE borrow');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE objet');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
