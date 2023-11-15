<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\LocationNameRepository;
use App\Repositories\LocationRepository;
use Illuminate\Database\Eloquent\Casts\Json;

class LocationManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $userRepository, $deviceService, $locationRepository, $locationNameRepository;

    function __construct(
        LocationRepository $locationRepository,
        LocationNameRepository $locationNameRepository
    ) {
        $this->locationRepository = $locationRepository;
        $this->locationNameRepository = $locationNameRepository;
    }

    public function deleteLocationName($id)
    {
        return $this->locationNameRepository->delete($id);
    }

    public function getCustomerLocations($customer_id)
    {
        return $this->locationNameRepository->getLocationNamesAsPerCustomer($customer_id);
    }
}
