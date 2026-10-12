<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261012185353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(20) DEFAULT NULL, birth_date DATE DEFAULT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, status VARCHAR(20) NOT NULL, client_id INT NOT NULL, training_program_id INT NOT NULL, INDEX IDX_86FFD28519EB6921 (client_id), INDEX IDX_86FFD2858406BD6C (training_program_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, method VARCHAR(20) NOT NULL, paid_at DATETIME NOT NULL, membership_id INT NOT NULL, INDEX IDX_6D28840D1FB354CD (membership_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE trainer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(20) DEFAULT NULL, specialization VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_C5150820E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE training_program (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, duration_weeks SMALLINT NOT NULL, price NUMERIC(10, 2) NOT NULL, trainer_id INT NOT NULL, INDEX IDX_4FD3E78AFB08EDF6 (trainer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD28519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2858406BD6C FOREIGN KEY (training_program_id) REFERENCES training_program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1FB354CD FOREIGN KEY (membership_id) REFERENCES membership (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE training_program ADD CONSTRAINT FK_4FD3E78AFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD28519EB6921');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD2858406BD6C');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1FB354CD');
        $this->addSql('ALTER TABLE training_program DROP FOREIGN KEY FK_4FD3E78AFB08EDF6');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE trainer');
        $this->addSql('DROP TABLE training_program');
    }
}
