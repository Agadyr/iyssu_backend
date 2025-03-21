<?php

use App\Migrations\UserProductMigration;

return new class extends UserProductMigration
{
    protected string $tableName = 'favorites';
};
