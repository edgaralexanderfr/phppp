<?php

declare(strict_types=1);

namespace PHPIO;

/**
 * @property array $content
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

    public function copy(Path $dest_path): void
    {
        if ($this->full_path === false || $dest_path->full_path === false) {
            return;
        }

        $full_path = $this->full_path;
        $content = $this->content;
        $full_path_length = strlen($full_path);

        foreach ($content as $file) {
            $sub_path = substr($file->full_path, $full_path_length);
            $sub_path_dirs = explode(DIRECTORY_SEPARATOR, $sub_path);
            array_pop($sub_path_dirs);
            $sub_dir = implode(DIRECTORY_SEPARATOR, $sub_path_dirs);
            $full_dest_path = $dest_path->full_path . DIRECTORY_SEPARATOR . $sub_dir;
            $full_dest_file = $dest_path->full_path . DIRECTORY_SEPARATOR . $sub_path;

            @mkdir(
                directory: $full_dest_path,
                recursive: true
            );

            @copy($file->full_path, $full_dest_file);
        }
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
    public array $content {
        /** @disregard */
        get {
            $content = [];
            $files = $this->files;

            if ($files !== false) {
                foreach ($files as $file) {
                    if ($file instanceof File) {
                        $content[] = $file;
                    } else if ($file instanceof self) {
                        $content = array_merge($content, $file->content);
                    }
                }
            }

            return $content;
        }
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
