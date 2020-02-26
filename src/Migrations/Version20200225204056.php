<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225204056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE time_dimension');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE time_dimension (id INT NOT NULL, db_date DATE NOT NULL, year INT NOT NULL, month INT NOT NULL, day INT NOT NULL, quarter INT NOT NULL, week INT NOT NULL, day_name VARCHAR(9) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, day_of_week INT NOT NULL, month_name VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, holiday_flag CHAR(1) CHARACTER SET utf8mb4 DEFAULT \'f\' COLLATE `utf8mb4_0900_ai_ci`, weekend_flag CHAR(1) CHARACTER SET utf8mb4 DEFAULT \'f\' COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX td_dbdate_idx (db_date), UNIQUE INDEX td_ymd_idx (year, month, day), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
