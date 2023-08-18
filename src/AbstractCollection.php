<?php

namespace PHPCollection;

abstract class AbstractCollection implements Collection
{
	protected array $elements = [];

	public function count(): int
	{
		return count($this->elements);
	}

	public function clear(): void
	{
		$this->elements = [];
	}

	public function isEmpty(): bool
	{
		return empty($this->elements);
	}

	public function toArray(): array
	{
		return $this->elements;
	}

	public function getIterator(): \Traversable
	{
		return new \ArrayIterator($this->elements);
	}
}