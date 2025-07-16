<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250716RecurringExpense extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add RecurringExpense entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE recurring_expense (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                calendar_id INT NOT NULL,
                label VARCHAR(255) NOT NULL,
                amount DOUBLE PRECISION NOT NULL,
                category_id INT DEFAULT NULL,
                start_date DATE NOT NULL,
                interval VARCHAR(16) NOT NULL,
                end_date DATE DEFAULT NULL,
                is_active TINYINT(1) NOT NULL,
                INDEX IDX_RECURRING_EXPENSE_USER (user_id),
                INDEX IDX_RECURRING_EXPENSE_CALENDAR (calendar_id),
                INDEX IDX_RECURRING_EXPENSE_CATEGORY (category_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE recurring_expense
                ADD CONSTRAINT FK_RECURRING_EXPENSE_USER FOREIGN KEY (user_id) REFERENCES user (id),
                ADD CONSTRAINT FK_RECURRING_EXPENSE_CALENDAR FOREIGN KEY (calendar_id) REFERENCES calendar (id),
                ADD CONSTRAINT FK_RECURRING_EXPENSE_CATEGORY FOREIGN KEY (category_id) REFERENCES category (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE recurring_expense');
    }
}
