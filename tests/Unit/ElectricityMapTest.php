<?php

namespace Tests\Feature;

use App\Enums\CarbonIntensityState;
use App\Services\ElectricityMapsService;
use Carbon\Doctrine\CarbonImmutableType;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class ElectricityMapTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_GetCarbonIntensity_Successful()
    {
        // Mock the configuration values
        Config::set('app.electricity_maps.api_url', 'https://api.electricitymaps.com/');
        Config::set('app.electricity_maps.carbon_intentsity_url', value: 'carbon-intensity');
        Config::set('app.electricity_maps_api_key', 'test_key');

        // Mock a successful HTTP response
        Http::fake([
            'https://api.electricitymaps.com/carbon-intensity?zone=DE' => Http::response([
                'carbonIntensity' => 250,
            ], 200),
        ]);

        // Call the function
        $service = new ElectricityMapsService();
        $intensity = $service->getCarbonIntensity('DE');

        // Assert that the response is correct
        $this->assertEquals(250, $intensity);
    }

    public function test_GetCarbonIntensity_Failure() {

        Config::set('app.electricity_maps.api_url', 'https://api.electricitymaps.com/');
        Config::set('app.electricity_maps.carbon_intentsity_url', 'carbon-intensity');
        Config::set('app.electricity_maps_api_key', 'test_key');

        // Mock an unsuccessful HTTP response
        Http::fake([
            'https://api.electricitymaps.com/carbon-intensity?zone=DE' => Http::response([], 500),
        ]);

        // Mock the log
        Log::shouldReceive('warning')
            ->once()
            ->with("API returned null or error for zone: DE.");

        // Call the function
        $service = new ElectricityMapsService();
        $intensity = $service->getCarbonIntensity('DE');

        // Assert that the response is null
        $this->assertEquals(null, $intensity);
    }

    public function test_Mock_Red_Light(): void
    {
        // Mock the service
        $mockService = Mockery::mock(ElectricityMapsService::class);
        $mockService->shouldReceive('getCarbonIntensity')
            ->with('DE')
            ->andReturn(450);
        
        $mockService->shouldReceive('getCarbonIntensityStatus')
            ->with(450)
            ->andReturn(CarbonIntensityState::RED->value);

        $this->app->instance(ElectricityMapsService::class, $mockService);

        $response = $this->get('/'); 

        // Assert the view data
        $response->assertViewHas('intensity', value: 450);
        $response->assertViewHas('status', CarbonIntensityState::RED->value);
    }

    public function test_Mock_Yellow_Light(): void
    {
        // Mock the service
        $mockService = Mockery::mock(ElectricityMapsService::class);
        $mockService->shouldReceive('getCarbonIntensity')
            ->with('DE')
            ->andReturn(200);

        $this->app->instance(ElectricityMapsService::class, $mockService);

        $mockService->shouldReceive('getCarbonIntensityStatus')
            ->with(200)
            ->andReturn(CarbonIntensityState::YELLOW->value);

        $response = $this->get('/'); 

        // Assert the view data
        $response->assertViewHas('intensity', value: 200);
        $response->assertViewHas('status', CarbonIntensityState::YELLOW->value);
    }
}
