<?php

namespace Propaganistas\LaravelPhone\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Traits\ParsesCountries;

class PhoneNumberCast implements CastsAttributes
{
    use ParsesCountries;

    /**
     * The provided phone country.
     *
     * @var array
     */
    protected $countries = [];

    /**
     * PhoneNumberCast constructor.
     *
     * @param $country
     */
    public function __construct($country)
    {
        $this->countries = is_array($country) ? $country : func_get_args();
    }

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Contracts\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     *
     * @return \Propaganistas\LaravelPhone\PhoneNumber
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $parameters = array_unique([$this->countries, $key.'_country']);

        $countries = array_map(function($item) use ($attributes) {
            return $attributes[$item] ?? $item;
        }, $parameters);

        return PhoneNumber::make($value, $this->parseCountries($countries));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     *
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof PhoneNumber) {
            return $value->getRawNumber();
        }

        return (string) $value;
    }
}
