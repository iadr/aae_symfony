<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225165058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_user (subject_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1F59529223EDC87 (subject_id), INDEX IDX_1F595292A76ED395 (user_id), PRIMARY KEY(subject_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutor_hours (id INT AUTO_INCREMENT NOT NULL, tutor_id_id INT NOT NULL, hour TIME NOT NULL, day_of_week VARCHAR(9) NOT NULL, INDEX IDX_5B8FCF3FAED1ECE5 (tutor_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subject_user ADD CONSTRAINT FK_1F59529223EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_user ADD CONSTRAINT FK_1F595292A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutor_hours ADD CONSTRAINT FK_5B8FCF3FAED1ECE5 FOREIGN KEY (tutor_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subject_user DROP FOREIGN KEY FK_1F59529223EDC87');
        $this->addSql('ALTER TABLE subject_user DROP FOREIGN KEY FK_1F595292A76ED395');
        $this->addSql('ALTER TABLE tutor_hours DROP FOREIGN KEY FK_5B8FCF3FAED1ECE5');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_user');
        $this->addSql('DROP TABLE tutor_hours');
        $this->addSql('DROP TABLE user');
    }
}
