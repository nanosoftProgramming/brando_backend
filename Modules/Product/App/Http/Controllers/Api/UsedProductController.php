<?php

namespace Modules\Product\App\Http\Controllers\Api;

use Exception;
use Illuminate\Routing\Controller;
use Modules\Product\App\Http\Requests\UsedProductRequest;
use Modules\Product\App\Resources\UsedProductResource;
use Modules\Product\DTO\UsedProductDto;
use Modules\Product\Service\UsedProductService;

class UsedProductController extends Controller
{
    public function __construct(protected UsedProductService $service)
    {
        $this->middleware('auth:client');
    }

    public function index()
    {
        try {
            $usedProducts = $this->service->findAll(request()->all());

            return returnMessage(true, 'Used products fetched successfully', UsedProductResource::collection($usedProducts));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function store(UsedProductRequest $request)
    {
        try {
            $dto = new UsedProductDto($request);
            $data = $dto->dataFromRequest();
            $usedProduct = $this->service->create($data);

            return returnMessage(true, 'Used product created successfully', new UsedProductResource($usedProduct));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function show($id)
    {
        try {
            $usedProduct = $this->service->findById($id);

            return returnMessage(true, 'Used product fetched successfully', new UsedProductResource($usedProduct));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(UsedProductRequest $request, $id)
    {
        try {
            $dto = new UsedProductDto($request);
            $data = $dto->dataFromRequest();
            $usedProduct = $this->service->update($id, $data);

            return returnMessage(true, 'Used product updated successfully', new UsedProductResource($usedProduct));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);

            return returnMessage(true, 'Used product deleted successfully');
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function myListings()
    {
        try {
            $clientId = auth('client')->id();
            $usedProducts = \Modules\Product\App\Models\UsedProduct::where('client_id', $clientId)->get();

            return returnMessage(true, 'My listings fetched successfully', UsedProductResource::collection($usedProducts));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
