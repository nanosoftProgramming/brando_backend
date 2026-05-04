<?php

namespace Modules\Common\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\App\resources\HistoryResource;
use Modules\Common\Service\HistoryService;

class HistoryController extends Controller
{
    private $historyService;

    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $histories = $this->historyService->findAll($data);

        return returnMessage(true, 'Histories fetched successfully', HistoryResource::collection($histories)->response()->getData(true));
    }
}
