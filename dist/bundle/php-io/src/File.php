<?php

declare(strict_types=1);

namespace PHPIO;

/**
 * @property string|false $content
 */
class File implements \JsonSerializable, \Stringable
{
    public readonly string|false $full_path;
    public readonly string $file_name;

    private string|false|null $cached_content = null;

    public function __construct(
        public readonly string $path,
    ) {
        $this->full_path = realpath($path);
        $this->file_name = basename($path);
    }

    public function getContent(bool $use_include_path = false, $context = null, int $offset = 0, ?int $length = null): string|false
    {
        return file_get_contents($this->path, $use_include_path, $context, $offset, $length);
    }

    public function jsonSerialize(): mixed
    {
        return $this->file_name;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    /** @disregard */
    public string|false $content
    {
        /** @disregard */
        get {
            if ($this->cached_content === null) {
                $this->cached_content = $this->getContent();
            }

            return $this->cached_content;
        }
    }
}
