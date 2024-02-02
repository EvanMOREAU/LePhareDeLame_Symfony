<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124114220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv_user (rdv_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3F419EE14CCE3F86 (rdv_id), INDEX IDX_3F419EE1A76ED395 (user_id), PRIMARY KEY(rdv_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv_service (rdv_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_668BE04D4CCE3F86 (rdv_id), INDEX IDX_668BE04DED5CA9E6 (service_id), PRIMARY KEY(rdv_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rdv_user ADD CONSTRAINT FK_3F419EE14CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rdv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rdv_user ADD CONSTRAINT FK_3F419EE1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rdv_service ADD CONSTRAINT FK_668BE04D4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rdv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rdv_service ADD CONSTRAINT FK_668BE04DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rdv_user DROP FOREIGN KEY FK_3F419EE14CCE3F86');
        $this->addSql('ALTER TABLE rdv_user DROP FOREIGN KEY FK_3F419EE1A76ED395');
        $this->addSql('ALTER TABLE rdv_service DROP FOREIGN KEY FK_668BE04D4CCE3F86');
        $this->addSql('ALTER TABLE rdv_service DROP FOREIGN KEY FK_668BE04DED5CA9E6');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE rdv_user');
        $this->addSql('DROP TABLE rdv_service');
    }
}
