<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920185158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alternativas (id UUID NOT NULL, questao_id UUID DEFAULT NULL, alternativa VARCHAR(255) NOT NULL, is_correta BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2204EE50CB1A8E7E ON alternativas (questao_id)');
        $this->addSql('COMMENT ON COLUMN alternativas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN alternativas.questao_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE alternativas_prova (id UUID NOT NULL, questao_id UUID DEFAULT NULL, alternativa VARCHAR(255) NOT NULL, is_correta BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5B64E46ACB1A8E7E ON alternativas_prova (questao_id)');
        $this->addSql('COMMENT ON COLUMN alternativas_prova.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN alternativas_prova.questao_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE alunos (id UUID NOT NULL, nome VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN alunos.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE provas (id UUID NOT NULL, aluno_id UUID DEFAULT NULL, tema_id UUID DEFAULT NULL, nota DOUBLE PRECISION DEFAULT NULL, submetida_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(255) DEFAULT \'Aberta\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF21961AB2DDF7F4 ON provas (aluno_id)');
        $this->addSql('CREATE INDEX IDX_BF21961AA64A8A17 ON provas (tema_id)');
        $this->addSql('COMMENT ON COLUMN provas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN provas.aluno_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN provas.tema_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE questoes (id UUID NOT NULL, tema_id UUID DEFAULT NULL, pergunta VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B93B1AFA64A8A17 ON questoes (tema_id)');
        $this->addSql('COMMENT ON COLUMN questoes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN questoes.tema_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE questoes_prova (id UUID NOT NULL, prova_id UUID DEFAULT NULL, resposta_correta VARCHAR(255) NOT NULL, pergunta VARCHAR(255) NOT NULL, resposta_aluno VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2AA5F52E272FAB5F ON questoes_prova (prova_id)');
        $this->addSql('COMMENT ON COLUMN questoes_prova.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN questoes_prova.prova_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE temas (id UUID NOT NULL, nome VARCHAR(255) NOT NULL, slugname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN temas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE alternativas ADD CONSTRAINT FK_2204EE50CB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alternativas_prova ADD CONSTRAINT FK_5B64E46ACB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes_prova (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE provas ADD CONSTRAINT FK_BF21961AB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES alunos (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE provas ADD CONSTRAINT FK_BF21961AA64A8A17 FOREIGN KEY (tema_id) REFERENCES temas (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AFA64A8A17 FOREIGN KEY (tema_id) REFERENCES temas (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questoes_prova ADD CONSTRAINT FK_2AA5F52E272FAB5F FOREIGN KEY (prova_id) REFERENCES provas (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE provas DROP CONSTRAINT FK_BF21961AB2DDF7F4');
        $this->addSql('ALTER TABLE questoes_prova DROP CONSTRAINT FK_2AA5F52E272FAB5F');
        $this->addSql('ALTER TABLE alternativas DROP CONSTRAINT FK_2204EE50CB1A8E7E');
        $this->addSql('ALTER TABLE alternativas_prova DROP CONSTRAINT FK_5B64E46ACB1A8E7E');
        $this->addSql('ALTER TABLE provas DROP CONSTRAINT FK_BF21961AA64A8A17');
        $this->addSql('ALTER TABLE questoes DROP CONSTRAINT FK_2B93B1AFA64A8A17');
        $this->addSql('DROP TABLE alternativas');
        $this->addSql('DROP TABLE alternativas_prova');
        $this->addSql('DROP TABLE alunos');
        $this->addSql('DROP TABLE provas');
        $this->addSql('DROP TABLE questoes');
        $this->addSql('DROP TABLE questoes_prova');
        $this->addSql('DROP TABLE temas');
    }
}
