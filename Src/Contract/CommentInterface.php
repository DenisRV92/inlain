<?php

namespace Src\Contract;
interface CommentInterface
{
    public function getComments(): int;

    public function setComments(array $data): bool;

    public function searchText(string $str): array;
}