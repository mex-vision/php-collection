<?php

namespace PHPCollection;

interface Collection extends \IteratorAggregate, \Countable
{
	public function add(mixed $element): bool;
	public function addAll(Collection $collection): bool;
	public function clear(): void;
	public function contains(mixed $element): bool;
	public function containsAll(Collection $collection): bool;
	public function equals(Collection $collection): bool;
	public function isEmpty(): bool;
	public function remove(mixed $element, bool $all = false): bool;
	public function removeAll(Collection $collection, bool $all = false): bool;
	public function removeIf(callable $filter, bool $all = false): bool;
	public function retainAll(Collection $collection): bool;
	public function toArray(): array;
}