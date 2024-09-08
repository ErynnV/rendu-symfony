<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908094331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE step_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utensil_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recipe (id INT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at DATE NOT NULL, preview_filename VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA88B13712469DE2 ON recipe (category_id)');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
        $this->addSql('COMMENT ON COLUMN recipe.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE recipes_ingredients (recipe_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(recipe_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_761206B059D8A214 ON recipes_ingredients (recipe_id)');
        $this->addSql('CREATE INDEX IDX_761206B0933FE08C ON recipes_ingredients (ingredient_id)');
        $this->addSql('CREATE TABLE recipes_utensils (recipe_id INT NOT NULL, utensil_id INT NOT NULL, PRIMARY KEY(recipe_id, utensil_id))');
        $this->addSql('CREATE INDEX IDX_B526904859D8A214 ON recipes_utensils (recipe_id)');
        $this->addSql('CREATE INDEX IDX_B5269048EC4313DE ON recipes_utensils (utensil_id)');
        $this->addSql('CREATE TABLE step (id INT NOT NULL, recipe_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_43B9FE3C59D8A214 ON step (recipe_id)');
        $this->addSql('CREATE TABLE utensil (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipes_ingredients ADD CONSTRAINT FK_761206B059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipes_ingredients ADD CONSTRAINT FK_761206B0933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipes_utensils ADD CONSTRAINT FK_B526904859D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipes_utensils ADD CONSTRAINT FK_B5269048EC4313DE FOREIGN KEY (utensil_id) REFERENCES utensil (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utensil_id_seq CASCADE');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B13712469DE2');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE recipes_ingredients DROP CONSTRAINT FK_761206B059D8A214');
        $this->addSql('ALTER TABLE recipes_ingredients DROP CONSTRAINT FK_761206B0933FE08C');
        $this->addSql('ALTER TABLE recipes_utensils DROP CONSTRAINT FK_B526904859D8A214');
        $this->addSql('ALTER TABLE recipes_utensils DROP CONSTRAINT FK_B5269048EC4313DE');
        $this->addSql('ALTER TABLE step DROP CONSTRAINT FK_43B9FE3C59D8A214');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipes_ingredients');
        $this->addSql('DROP TABLE recipes_utensils');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE utensil');
    }
}
