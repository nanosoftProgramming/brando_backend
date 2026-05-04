<?php

namespace Modules\Common\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Common\App\Http\Requests\SliderRequest;
use Modules\Common\App\Models\Slider;
use Modules\Common\DTO\SliderDto;
use Modules\Common\Service\SliderService;

class SliderAdminController extends Controller
{
    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin')->only('toggleActivate');
        $this->sliderService = $sliderService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['restaurant', 'admin'];
        $sliders = $this->sliderService->findAll($data, $relations);

        return returnMessage(true, 'Sliders fetched successfully', $sliders);
    }

    public function store(SliderRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new SliderDto($request))->dataFromRequest();
            $slider = $this->sliderService->create($data);
            DB::commit();

            return returnMessage(true, 'Slider Created Successfully', $slider);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        try {
            DB::beginTransaction();
            $data = (new SliderDto($request))->dataFromRequest();
            $slider = $this->sliderService->update($slider, $data);
            DB::commit();

            return returnMessage(true, 'Slider Updated Successfully', $slider);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(SliderRequest $request, Slider $slider)
    {
        try {
            DB::beginTransaction();
            $this->sliderService->delete($slider);
            DB::commit();

            return returnMessage(true, 'Slider Deleted Successfully', null);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(Slider $slider)
    {
        try {
            DB::beginTransaction();
            $slider = $this->sliderService->toggleActivate($slider);
            DB::commit();

            return returnMessage(true, 'Slider Updated Successfully', $slider);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
