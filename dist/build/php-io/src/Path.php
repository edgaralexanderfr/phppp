<?php

declare(strict_types=1);

namespace PHPIO;

/**
 * @property array|false $ls
 * @property array|false $files
 */
class Path implements \Iterator, \JsonSerializable, \Stringable
{
    public readonly string|false $full_path;
    public readonly string $file_name;

    private array|false|null $cached_files = null;
    private array|null $walker = null;

    public function __construct(
        public readonly string $path,
        public bool $exclude_parent_dirs = true,
    ) {
        $this->full_path = realpath($path);
        $this->file_name = basename($path);
    }

    /**
     * @param ?resource $context
     */
    public function getFiles(int $sorting_order = SCANDIR_SORT_ASCENDING, $context = null): array|false
    {
        $dir = scandir($this->path, $sorting_order, $context);

        return $dir;
    }

    public function jsonSerialize(): mixed
    {
        return $this->file_name;
    }

    public function __toString(): string
    {
        if ($this->full_path === false) {
            return 'false';
        }

        return $this->full_path;
    }

    public function rewind(): void
    {
        if ($this->walker === null) {
            $this->walker = ($this->files) ? $this->files : [];
        }

        reset($this->walker);
    }

    #[\ReturnTypeWillChange]
    public function current(): mixed
    {
        return current($this->walker);
    }

    #[\ReturnTypeWillChange]
    public function key(): mixed
    {
        return key($this->walker);
    }

    public function next(): void
    {
        next($this->walker);
    }

    public function valid(): bool
    {
        return key($this->walker) !== null;
    }

    /** @disregard */
    public array|false $ls {
        /** @disregard */
        get => $this->files;
    }

    /** @disregard */
    public array|false $files {
        /** @disregard */
        get {
            if ($this->cached_files === null) {
                $this->cached_files = $this->getFiles();
            }

            if ($this->cached_files === false) {
                return $this->cached_files;
            }

            $files = [];

            foreach ($this->cached_files as $file) {
                if ($this->exclude_parent_dirs && ($file == '.' || $file == '..')) {
                    continue;
                }

                $path = "{$this->path}/{$file}";

                if (is_dir($path)) {
                    $files[] = new self($path);
                } else {
                    $files[] = new File($path);
                }
            }

            return $files;
        }
    }
}
