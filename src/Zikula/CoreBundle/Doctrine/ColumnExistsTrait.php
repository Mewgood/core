<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\CoreBundle\Doctrine;

use Doctrine\DBAL\Connection;

trait ColumnExistsTrait
{
    public function __construct(private readonly Connection $connection)
    {
    }

    private function columnExists(string $tableName, string $columnName): bool
    {
        $sm = $this->connection->getSchemaManager();
        $existingColumns = $sm->listTableColumns($tableName);
        foreach ($existingColumns as $existingColumn) {
            if ($existingColumn->getName() === $columnName) {
                return true;
            }
        }

        return false;
    }
}
