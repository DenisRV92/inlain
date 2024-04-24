<?php

namespace Src\Contract;

use PDO;

interface DbInterface
{
    public function connect(): void;

    public function count(string $table): int;

    public static function getInstance(): self;

    public function getConnection(): PDO;
}