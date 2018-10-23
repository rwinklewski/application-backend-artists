<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181023124101 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE album (token VARCHAR(6) NOT NULL, artist_id VARCHAR(6) NOT NULL, title VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, songs JSON NOT NULL, INDEX IDX_39986E43B7970CF8 (artist_id), UNIQUE INDEX album_token_uq (token), PRIMARY KEY(token)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist (token VARCHAR(6) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX artist_token_uq (token), PRIMARY KEY(token)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (token)');

        $this->addSql('CREATE TABLE token_ids (name VARCHAR(255) NOT NULL, next_id INT(10), UNIQUE INDEX token_ids_name_uq (name)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO token_ids (name, next_id) VALUES ("default", 1)');

        $this->addSql("
            CREATE FUNCTION GetNextTokenId(token_name VARCHAR(255)) RETURNS INT(10)
                DETERMINISTIC
            BEGIN
                DECLARE currentId INT(10);
                SELECT next_id INTO currentId FROM token_ids WHERE name=token_name;
                UPDATE token_ids SET next_id=(currentId+1) WHERE name=token_name;
                RETURN currentId;
            END
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43B7970CF8');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE artist');

        $this->addSql('DROP TABLE token_ids');
        $this->addSql('DROP FUNCTION GetNextTokenId');
    }
}
