<?php

namespace Modules\Restaurant\DTO;

class WorkingTimeDto
{
    public $working_times;

    public function __construct($request)
    {
        if ($request->has('working_times')) {
            $this->working_times = $request->get('working_times');
        }
    }

    public function dataFromRequest()
    {
        return $this->working_times ?? [];
    }
}
