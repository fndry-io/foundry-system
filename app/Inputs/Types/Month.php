<?php

namespace Foundry\System\Inputs\Types;

use Carbon\Carbon;
use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Month extends ChoiceInputType implements Field, FieldOptions
{

    /**
     * The input type for displaying on a page
     *
     * @return self|Inputable
     */
    static function input(): Inputable
    {
        return (new self(
            'month',
            __('Month'),
            false,
            static::options()
        ));
    }

    /**
     * The input options
     *
     * @param \Closure $closure A query builder to modify the query if needed
     * @param mixed $value
     *
     * @return array
     */
    static function options(\Closure $closure = null, $value = null): array
    {
        $dates = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::today()->firstOfMonth()->subMonths($i);
            array_push($dates, [
                'value' => $date->format('Y-m-d'),
                'text' => $date->format('M Y')
            ]);
        }

        return $dates;
    }
}
