<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds initial sources with links to the source table';
    }

    public function up(Schema $schema): void
    {
        // Добавляем источники с ссылками
        $this->addSql("INSERT INTO source (name, url) VALUES 
            ('РИА Новости', 'https://ria.ru/export/rss2/archive/index.xml'),
            ('Лента', 'https://lenta.ru/rss'),
            ('РБК', 'https://rssexport.rbc.ru/rbcnews/news/30/full.rss')
        ");
    }

    public function down(Schema $schema): void
    {
        // Удаляем добавленные источники
        $this->addSql("DELETE FROM source WHERE url IN (
            'https://ria.ru/export/rss2/archive/index.xml',
            'https://lenta.ru/rss',
            'https://rssexport.rbc.ru/rbcnews/news/30/full.rss'
        )");
    }
}