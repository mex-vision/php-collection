<?php

namespace PHPCollection;

use PHPCollection\Exception\IllegalArgumentException;
use PHPCollection\Exception\IndexOutOfBoundsException;

/**
 * @template T
 */
class ArrayList extends AbstractCollection
{
	/**
	 * @var array<int, T>
	 */
	protected array $elements = [];

	/**
	 * @param T $element
	 * @param int|null $index
	 * @return bool
	 */
	public function add(mixed $element, int $index = null): bool
	{
		if(is_null($index))
		{
			array_splice($this->elements, $this->count(), 0, [$element]);
			return true;
		}
		if($index < 0 or $index > $this->count())
			throw new IndexOutOfBoundsException();
		array_splice($this->elements, $index, 0, [$element]);
		return true;
	}

	/**
	 * @param Collection $collection
	 * @param int|null $index
	 * @return bool
	 */
	public function addAll(Collection $collection, int $index = null): bool
	{
		$result = false;
		foreach ($collection as $element)
		{
			$changed = $this->add($element, $index);
			if(!$result)
				$result = $changed;
			if(!is_null($index))
				$index++;
		}
		return $result;
	}

	/**
	 * @param T $element
	 * @return bool
	 */
	public function contains(mixed $element): bool
	{
		foreach ($this->elements as $e)
			if($element === $e)
				return true;
		return false;
	}

	/**
	 * @param Collection $collection
	 * @return bool
	 */
	public function containsAll(Collection $collection): bool
	{
		foreach ($collection as $element)
			if(!$this->contains($element))
				return false;
		return true;
	}

	/**
	 * @param Collection $collection
	 * @return bool
	 */
	public function equals(Collection $collection): bool
	{
		if($collection->count() != $this->count())
			return false;
		foreach ($collection as $index => $element)
			if($this->elements[$index] !== $element)
				return false;
		return true;
	}

	/**
	 * @param T $element
	 * @param bool $all
	 * @return bool
	 */
	public function remove(mixed $element, bool $all = false): bool
	{
		$removedElements = 0;
		for($i = 0; $i < count($this->elements); $i++)
		{
			if($this->elements[$i] == $element)
			{
				array_splice($this->elements, $i, 1);
				$removedElements++;
				if(!$all)
					return true;
				$i--;
			}
		}
		return $removedElements > 0;
	}

	/**
	 * @param int $index
	 * @return T
	 */
	public function removeIndex(int $index): mixed
	{
		if($index < 0 or $index >= $this->count())
			throw new IndexOutOfBoundsException();

		$result = $this->get($index);
		array_splice($this->elements, $index, 1);
		return $result;
	}

	/**
	 * @param Collection $collection
	 * @param bool $all
	 * @return bool
	 */
	public function removeAll(Collection $collection, bool $all = false): bool
	{
		$result = false;
		foreach ($collection as $element)
			$result = $this->remove($element, $all);
		return $result;
	}

	/**
	 * @param callable $filter
	 * @param bool $all
	 * @return bool
	 */
	public function removeIf(callable $filter, bool $all = false): bool
	{
		/**
		 * @var int $index
		 * @var T $element
		 */
		foreach ($this->elements as $index => $element)
		{
			$filterResult = $filter($element, $index);
			if($filterResult)
				return $this->remove($element, $all);
		}
		return false;
	}

	/**
	 * @param int $from
	 * @param int $to
	 * @return void
	 */
	public function removeRange(int $from, int $to): void
	{
		if($from < 0 or $from >= $this->count() or $to > $this->count() or $to < $from)
			throw new IndexOutOfBoundsException();
		array_splice($this->elements, $from, $to - $from);
	}

	/**
	 * @param Collection $collection
	 * @return bool
	 */
	public function retainAll(Collection $collection): bool
	{
		$deleteElements = [];
		foreach ($this->elements as $e)
			if(!$collection->contains($e))
				$deleteElements[] = $e;
		foreach ($deleteElements as $e)
			$this->remove($e, true);
		return count($deleteElements) > 0;
	}

	/**
	 * @param int $index
	 * @return T
	 */
	public function get(int $index): mixed
	{
		if(!array_key_exists($index, $this->elements))
			throw new IndexOutOfBoundsException();
		return $this->elements[$index];
	}

	/**
	 * @param T $element
	 * @return int
	 */
	public function indexOf(mixed $element): int
	{
		foreach ($this->elements as $index => $el)
			if($element === $el)
				return $index;
		return -1;
	}

	/**
	 * @param T $element
	 * @return int
	 */
	public function lastIndexOf(mixed $element): int
	{
		$result = -1;
		foreach ($this->elements as $index => $el)
			if($element === $el and $index > $result)
				$result = $index;
		return $result;
	}

	/**
	 * @param int $index
	 * @param T $element
	 * @return bool
	 */
	public function set(int $index, mixed $element): bool
	{
		if(!array_key_exists($index, $this->elements))
			throw new IndexOutOfBoundsException();
		if($this->elements[$index] === $element)
			return false;
		$this->elements[$index] = $element;
		return true;
	}

	/**
	 * @param int $from
	 * @param int $to
	 * @return ArrayList<T>
	 */
	public function subList(int $from, int $to): ArrayList
	{
		if($from < 0 or $to > $this->count())
			throw new IndexOutOfBoundsException();
		if($from > $to)
			throw new IllegalArgumentException();
		$result = new ArrayList();
		foreach ($this->elements as $index => $element)
			if($index >= $from and $index < $to)
				$result->add($element);
		return $result;
	}

	public static function ofArray(array $array): ArrayList
	{
		$arrayList = new ArrayList();
		foreach ($array as $item)
			$arrayList->add($item);
		return $arrayList;
	}
}