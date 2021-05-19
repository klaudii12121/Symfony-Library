<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519171101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_tags (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, tag_id INT DEFAULT NULL, INDEX IDX_7621DF2E16A2B381 (book_id), INDEX IDX_7621DF2EBAD26311 (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, publisher_id INT NOT NULL, book_name VARCHAR(255) NOT NULL, book_desc VARCHAR(1000) DEFAULT NULL, release_year INT DEFAULT NULL, book_icon VARCHAR(255) DEFAULT NULL, INDEX IDX_4A1B2A9212469DE2 (category_id), INDEX IDX_4A1B2A92F675F31B (author_id), INDEX IDX_4A1B2A9240C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrowings (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, borrow_date DATE NOT NULL, return_date DATE DEFAULT NULL, INDEX IDX_7547A7B4A76ED395 (user_id), INDEX IDX_7547A7B416A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishers (id INT AUTO_INCREMENT NOT NULL, publisher_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, tag_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role JSON NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_data (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, user_icon VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_627ABD6DE7927C74 (email), UNIQUE INDEX UNIQ_627ABD6DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_tags ADD CONSTRAINT FK_7621DF2E16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE book_tags ADD CONSTRAINT FK_7621DF2EBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9212469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9240C86FCE FOREIGN KEY (publisher_id) REFERENCES publishers (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B416A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE users_data ADD CONSTRAINT FK_627ABD6DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('ALTER TABLE book_tags DROP FOREIGN KEY FK_7621DF2E16A2B381');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B416A2B381');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9212469DE2');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9240C86FCE');
        $this->addSql('ALTER TABLE book_tags DROP FOREIGN KEY FK_7621DF2EBAD26311');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B4A76ED395');
        $this->addSql('ALTER TABLE users_data DROP FOREIGN KEY FK_627ABD6DA76ED395');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, cat_name VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, publisher_name VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag_name VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE book_tags');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE borrowings');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE publishers');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_data');
    }
}
