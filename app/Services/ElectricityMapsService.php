<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Enums\CarbonIntensityState;
use Exception;
use Illuminate\Support\Facades\Log;

class ElectricityMapsService
{

    public function getCarbonIntensity($zone): mixed
    {
        $intensity = null;

        try{
            $baseUrl = Config::get('app.electricity_maps.api_url');
            $method = Config::get('app.electricity_maps.carbon_intentsity_url');
             
            $response = Http::withHeaders([
                'auth-token' => Config::get('app.electricity_maps_api_key'),
            ])->get(
                $baseUrl.$method,
                [
                    'zone' => $zone,
                ]
            );
            
            if ($response->successful()) {
                
                $intensity = $response->json()['carbonIntensity'];
    
            }else{
    
                Log::warning(message: "API returned null or error for zone: {$zone}.");
                
            }
        }catch(Exception $ex)
        {
            Log::warning(message: "Expection Occured: {$ex->getMessage()}");
        }
        

        return $intensity;
    }

    public function getCarbonIntensityStatus(?int $intensity): ?string
    {
        if ($intensity === null) {
            return null; // Don't return anything
        }
        
        return match (true) {
            $intensity > 400 => CarbonIntensityState::RED->value,
            $intensity < 200 => CarbonIntensityState::GREEN->value,
            default => CarbonIntensityState::YELLOW->value,
        };

    }
}
