<?php

namespace Modules\Client\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\App\Models\Client;
use Modules\Client\App\resources\ClientResource;
use Modules\Client\Service\ClientService;

class ClientAdminController extends Controller
{
    protected $clientService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ClientService $clientService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin');
        $this->clientService = $clientService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $clients = $this->clientService->findAll($data);

        return returnMessage(true, 'Clients fetched successfully', ClientResource::collection($clients)->response()->getData(true));
    }

    public function toggleActivate(Request $request, Client $client)
    {
        try {
            DB::beginTransaction();
            $client = $this->clientService->toggleActivate($client);
            DB::commit();

            return returnMessage(true, 'Client updated successfully', null);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
