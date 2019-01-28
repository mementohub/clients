<?php

namespace iMemento\Clients\Responses;

trait CollectionInterceptor
{
    /**
     * The following methods are intercepted to always return base collections.
     */
    public function median($key = null)
    {
        return $this->toBase()->median($key);
    }

    public function collapse()
    {
        return $this->toBase()->collapse();
    }

    public function crossJoin(...$lists)
    {
        return $this->toBase()->crossJoin($lists);
    }

    public function diff($items)
    {
        return $this->toBase()->diff($items);
    }

    public function diffUsing($items, callable $callback)
    {
        return $this->toBase()->diffUsing($items, $callback);
    }

    public function diffAssoc($items)
    {
        return $this->toBase()->diffAssoc($items);
    }

    public function diffAssocUsing($items, callable $callback)
    {
        return $this->toBase()->diffAssocUsing($items, $callback);
    }

    public function diffKeys($items)
    {
        return $this->toBase()->diffKeys($items);
    }

    public function diffKeysUsing($items, callable $callback)
    {
        return $this->toBase()->diffKeysUsing($items, $callback);
    }

    public function except($keys)
    {
        return $this->toBase()->except($keys);
    }

    public function filter(callable $callback = null)
    {
        return $this->toBase()->filter($callback);
    }

    public function flatten($depth = INF)
    {
        return $this->toBase()->flatten($depth);
    }

    public function flip()
    {
        return $this->toBase()->flip();
    }

    public function groupBy($groupBy, $preserveKeys = false)
    {
        return $this->toBase()->groupBy($groupBy, $preserveKeys);
    }

    public function keyBy($keyBy)
    {
        return $this->toBase()->keyBy($keyBy);
    }

    public function intersect($items)
    {
        return $this->toBase()->intersect($items);
    }

    public function intersectByKeys($items)
    {
        return $this->toBase()->intersectByKeys($items);
    }

    public function keys()
    {
        return $this->toBase()->keys();
    }

    public function pluck($value, $key = null)
    {
        return $this->toBase()->pluck($value, $key);
    }

    public function map(callable $callback)
    {
        return $this->toBase()->map($callback);
    }

    public function mapToDictionary(callable $callback)
    {
        return $this->toBase()->mapToDictionary($callback);
    }

    public function mapWithKeys(callable $callback)
    {
        return $this->toBase()->mapWithKeys($callback);
    }

    public function merge($items)
    {
        return $this->toBase()->merge($items);
    }

    public function combine($values)
    {
        return $this->toBase()->combine($values);
    }

    public function union($items)
    {
        return $this->toBase()->union($items);
    }

    public function nth($step, $offset = 0)
    {
        return $this->toBase()->nth($step, $offset);
    }
    
    public function only($keys)
    {
        return $this->toBase()->only($keys);
    }

    public function partition($key, $operator = null, $value = null)
    {
        return $this->toBase()->partition($key, $operator, $value);
    }

    public function concat($source)
    {
        return $this->toBase()->concat($source);
    }

    public function random($number = null)
    {
        return $this->toBase()->random($number);
    }

    public function reverse()
    {
        return $this->toBase()->reverse();
    }

    public function shuffle($seed = null)
    {
        return $this->toBase()->shuffle($seed);
    }

    public function slice($offset, $length = null)
    {
        return $this->toBase()->slice($offset, $length);
    }

    public function split($numberOfGroups)
    {
        return $this->toBase()->split($numberOfGroups);
    }

    public function chunk($size)
    {
        return $this->toBase()->chunk($size);
    }

    public function sort(callable $callback = null)
    {
        return $this->toBase()->sort($callback);
    }

    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        return $this->toBase()->sort($callback, $options, $descending);
    }

    public function sortKeys($options = SORT_REGULAR, $descending = false)
    {
        return $this->toBase()->sortKeys($options, $descending);
    }

    public function splice($offset, $length = null, $replacement = [])
    {
        return $this->toBase()->splice($offset, $length, $replacement);
    }

    public function tap(callable $callback)
    {
        return $this->toBase()->tap($callback);
    }

    public function values()
    {
        return $this->toBase()->values();
    }

    public function zip($items)
    {
        return $this->toBase()->zip($items);
    }

    public function pad($size, $value)
    {
        return $this->toBase()->pad($size, $value);
    }

}
