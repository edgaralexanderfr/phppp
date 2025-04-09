<?php

declare(strict_types=1);

namespace PHPTypes\List;

use Iterator;

/**
 * Doubly-Linked circular list.
 *
 * @property ?LinkedListNode $first
 * @property ?LinkedListNode $last
 * @property int $count
 * @property int $version
 */
class LinkedList implements Iterator
{
    private ?LinkedListNode $head = null;
    private int $c = 0;
    private int $v = 0;
    private ?LinkedListNode $walker = null;
    private int $index = 0;

    public function addFirst(mixed $value): LinkedListNode
    {
        $result = new LinkedListNode($value, $this);

        if ($this->head == null) {
            $this->insertNodeToEmptyList($result);
        } else {
            $this->insertNodeBefore($this->head, $result);
            $this->head = $result;
        }

        return $result;
    }

    public function addLast(mixed $value): LinkedListNode
    {
        $result = new LinkedListNode($value, $this);

        if ($this->head == null) {
            $this->insertNodeToEmptyList($result);
        } else {
            $this->insertNodeBefore($this->head, $result);
        }

        return $result;
    }

    private function insertNodeBefore(LinkedListNode $node, LinkedListNode $new_node): void
    {
        $new_node->next = $node;
        $new_node->previous = $node->previous;

        if ($node->previous) {
            $node->previous->next = $new_node;
        }

        $node->previous = $new_node;

        $this->v++;
        $this->c++;
    }

    private function insertNodeToEmptyList(LinkedListNode $new_node)
    {
        $new_node->next = $new_node;
        $new_node->previous = $new_node;

        $this->head = $new_node;
        $this->v++;
        $this->c++;
    }

    public function rewind(): void
    {
        $this->walker = $this->head;
        $this->index = 0;
    }

    #[\ReturnTypeWillChange]
    public function current(): mixed
    {
        return $this->walker->value;
    }

    #[\ReturnTypeWillChange]
    public function key(): mixed
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->walker = $this->walker->next;
        $this->index++;
    }

    public function valid(): bool
    {
        return $this->index < $this->c;
    }

    /** @disregard */
    public ?LinkedListNode $first
    {
        /** @disregard */
        get => $this->head;
    }

    /** @disregard */
    public ?LinkedListNode $last
    {
        /** @disregard */
        get => $this->head?->previous;
    }

    /** @disregard */
    public int $count
    {
        /** @disregard */
        get => $this->c;
    }

    /** @disregard */
    public int $version
    {
        /** @disregard */
        get => $this->v;
    }
}
