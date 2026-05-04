<?php

namespace Modules\Client\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\App\Http\Requests\AddressRequest;
use Modules\Client\App\Models\Address;
use Modules\Client\App\resources\AddressResource;
use Modules\Client\DTO\AddressDto;
use Modules\Client\Service\AddressService;

class AddressController extends Controller
{
    private $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->middleware('auth:client');
        $this->addressService = $addressService;
    }

    public function index(Request $request)
    {
        $addresses = $this->addressService->findAll($request->all());

        return returnMessage(true, 'Addresses fetched successfully', AddressResource::collection($addresses));
    }

    public function store(AddressRequest $request)
    {
        try {
            DB::beginTransaction();

            $addressData = (new AddressDto($request))->dataFromRequest();
            $address = $this->addressService->create($addressData);

            DB::commit();

            return returnMessage(true, 'Address created successfully', new AddressResource($address));
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function update(AddressRequest $request, Address $address)
    {
        try {
            DB::beginTransaction();

            $addressData = (new AddressDto($request))->dataFromRequest();
            $updated = $this->addressService->update($address, $addressData);

            DB::commit();

            return returnMessage(true, 'Address updated successfully', new AddressResource($updated));
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy(Address $address)
    {
        try {
            DB::beginTransaction();

            $this->addressService->delete($address);

            DB::commit();

            return returnMessage(true, 'Address deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }
}
