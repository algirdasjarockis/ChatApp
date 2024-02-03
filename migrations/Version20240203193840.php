<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203193840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9BA0E79C3');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F4A3353D8');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4A3353D8 FOREIGN KEY (app_user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B119AC0396');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B114A3353D8');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B119AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B114A3353D8 FOREIGN KEY (app_user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b119ac0396');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b114a3353d8');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b119ac0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b114a3353d8 FOREIGN KEY (app_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e9ba0e79c3');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e9ba0e79c3 FOREIGN KEY (last_message_id) REFERENCES message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307f4a3353d8');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307f9ac0396');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307f4a3353d8 FOREIGN KEY (app_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307f9ac0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
