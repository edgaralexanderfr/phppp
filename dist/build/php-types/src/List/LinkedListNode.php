<?php

declare(strict_types=1);

namespace PHPTypes\List;

final class LinkedListNode
{
    public ?LinkedListNode $next = null;
    public ?LinkedListNode $previous = null;

    public function __construct(
        public mixed $value = null,
        public ?LinkedList $list = null,
    ) {}
}
