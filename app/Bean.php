<?php namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Bean object based on Collection
 */
class Bean extends Collection
{
    public function __construct($items)
    {
        if (is_array($items) || is_object($items)) {
            $items = json_decode(json_encode($items), true);
        } elseif (is_string($items)) {
            $items = json_decode($items, true);
        }

        parent::__construct($items);
    }

    public function get($key, $default = null)
    {
        return array_get($this->items, $key, $default);
    }

    public function set($key, $value)
    {
        return array_set($this->items, $key, $value);
    }

    public function toObject()
    {
        return json_decode($this->toJson());
    }

    public function date($key)
    {
        $date = $this->get($key);
        $dateArray = explode('/', $date);
        if (count($dateArray) >= 3) {
            return Carbon::createFromDate(intval($dateArray[2]), intval($dateArray[1]), intval($dateArray[0]));
        }
        return null;
    }
}
