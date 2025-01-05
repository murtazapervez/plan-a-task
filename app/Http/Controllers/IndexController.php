<?php

namespace App\Http\Controllers;

use App\Enums\CarbonIntensityState;
use App\Services\ElectricityMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{

    public function __construct(private readonly ElectricityMapsService $electricityMapsService)
    {
    }
    
    public function index()
    {
        //Passing zone as request parameter
        $zone = request('zone', default: 'DE');
        
        //Calling the service
        $intensity = $this->electricityMapsService->getCarbonIntensity($zone);
        
        $status = $this->electricityMapsService->getCarbonIntensityStatus($intensity); 

        return view('index', data: compact('intensity','status'));
    }
}
