<?php

namespace Src\Contract;

interface PostInterface
{
    public function getPosts(): int;

    public function setPosts(array $data): bool;
}