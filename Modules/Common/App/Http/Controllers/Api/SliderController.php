<?php

namespace Modules\Common\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Service\SliderService;

class SliderController extends Controller
{
    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['restaurant', 'admin'];
        $sliders = $this->sliderService->active($data, $relations);

        return returnMessage(true, 'Sliders fetched successfully', $sliders);
    }
}
