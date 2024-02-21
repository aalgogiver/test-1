<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220154501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO product (id, name, price) VALUES(1, 'Iphone', 100.00)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES(2, 'Наушники', 20.00)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES(3, 'Чехол', 10.00)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM product');
    }
}
