<?php

namespace Modules\Product\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Http\Requests\AttributeRequest;
use Modules\Product\App\Models\Attribute;
use Modules\Product\DTO\AttributeDto;
use Modules\Product\Service\AttributeService;

class AttributeAdminController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin');

        $this->attributeService = $attributeService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['values'];
        $attributes = $this->attributeService->findAll($data, $relations);

        return returnMessage(true, 'Attributes fetched successfully', $attributes);
    }

    public function store(AttributeRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new AttributeDto($request))->dataFromRequest();
            $attribute = $this->attributeService->create($data);
            DB::commit();

            return returnMessage(true, 'Attribute created successfully', $attribute);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        try {
            DB::beginTransaction();
            $data = (new AttributeDto($request))->dataFromRequest();
            $attribute = $this->attributeService->update($attribute, $data);
            DB::commit();

            return returnMessage(true, 'Attribute updated successfully', $attribute);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(AttributeRequest $request, Attribute $attribute)
    {
        try {
            DB::beginTransaction();
            $this->attributeService->delete($attribute);
            DB::commit();

            return returnMessage(true, 'Attribute deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function activate(AttributeRequest $request, Attribute $attribute)
    {
        try {
            DB::beginTransaction();
            $this->attributeService->activate($attribute);
            DB::commit();

            return returnMessage(true, 'Attribute activated successfully', $attribute);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
