<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914013858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alternativas (id UUID NOT NULL, questao_id UUID DEFAULT NULL, resposta VARCHAR(255) NOT NULL, is_correta BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2204EE50CB1A8E7E ON alternativas (questao_id)');
        $this->addSql('COMMENT ON COLUMN alternativas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN alternativas.questao_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE alunos (id UUID NOT NULL, nome VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN alunos.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE alunos_provas (aluno_id UUID NOT NULL, prova_id UUID NOT NULL, PRIMARY KEY(aluno_id, prova_id))');
        $this->addSql('CREATE INDEX IDX_CB69D843B2DDF7F4 ON alunos_provas (aluno_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB69D843272FAB5F ON alunos_provas (prova_id)');
        $this->addSql('COMMENT ON COLUMN alunos_provas.aluno_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN alunos_provas.prova_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE provas (id UUID NOT NULL, status VARCHAR(255) DEFAULT \'Aberta\' NOT NULL, nota DOUBLE PRECISION DEFAULT NULL, submetida_em TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, respostas_aluno jsonb DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN provas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE prova_questoes (prova_id UUID NOT NULL, questao_id UUID NOT NULL, PRIMARY KEY(prova_id, questao_id))');
        $this->addSql('CREATE INDEX IDX_7052E7AB272FAB5F ON prova_questoes (prova_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7052E7ABCB1A8E7E ON prova_questoes (questao_id)');
        $this->addSql('COMMENT ON COLUMN prova_questoes.prova_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN prova_questoes.questao_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE questoes (id UUID NOT NULL, tema_id UUID DEFAULT NULL, pergunta VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B93B1AFA64A8A17 ON questoes (tema_id)');
        $this->addSql('COMMENT ON COLUMN questoes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN questoes.tema_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE temas (id UUID NOT NULL, name VARCHAR(255) NOT NULL, slugname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN temas.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE alternativas ADD CONSTRAINT FK_2204EE50CB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alunos_provas ADD CONSTRAINT FK_CB69D843B2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES alunos (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alunos_provas ADD CONSTRAINT FK_CB69D843272FAB5F FOREIGN KEY (prova_id) REFERENCES provas (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prova_questoes ADD CONSTRAINT FK_7052E7AB272FAB5F FOREIGN KEY (prova_id) REFERENCES provas (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prova_questoes ADD CONSTRAINT FK_7052E7ABCB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AFA64A8A17 FOREIGN KEY (tema_id) REFERENCES temas (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE alunos_provas DROP CONSTRAINT FK_CB69D843B2DDF7F4');
        $this->addSql('ALTER TABLE alunos_provas DROP CONSTRAINT FK_CB69D843272FAB5F');
        $this->addSql('ALTER TABLE prova_questoes DROP CONSTRAINT FK_7052E7AB272FAB5F');
        $this->addSql('ALTER TABLE alternativas DROP CONSTRAINT FK_2204EE50CB1A8E7E');
        $this->addSql('ALTER TABLE prova_questoes DROP CONSTRAINT FK_7052E7ABCB1A8E7E');
        $this->addSql('ALTER TABLE questoes DROP CONSTRAINT FK_2B93B1AFA64A8A17');
        $this->addSql('DROP TABLE alternativas');
        $this->addSql('DROP TABLE alunos');
        $this->addSql('DROP TABLE alunos_provas');
        $this->addSql('DROP TABLE provas');
        $this->addSql('DROP TABLE prova_questoes');
        $this->addSql('DROP TABLE questoes');
        $this->addSql('DROP TABLE temas');
    }
}
