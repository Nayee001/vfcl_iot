<?php

namespace App\Repositories;

use App\Interfaces\LocationNameRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LocationNameRepository implements LocationNameRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function create($location, $request)
    {
        $locationNames = [];

        foreach ($request['location_name'] as $name) {
            $locationNames[] = [
                'user_id' => $location['user_id'],
                'device_id' => null,
                'location_id' => $location['id'],
                'location_name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (!empty($locationNames)) {
            $userLocations = $this->model::insert($locationNames);
            return $userLocations;
        }
        return null;
    }
    public function update($userAddress, $request)
    {
        // Use a transaction to ensure data integrity
        DB::transaction(function () use ($userAddress, $request) {
            // First, delete existing names for the user
            $deleteCount = $this->model::where('user_id', $userAddress['user_id'])->delete();

            // Check if delete operation was successful or necessary
            if ($deleteCount > 0 || $this->model::where('user_id', $userAddress['user_id'])->doesntExist()) {
                $locationData = collect($request['location_name'])->map(function ($name) use ($userAddress) {
                    return [
                        'user_id' => $userAddress['user_id'],
                        'location_id' => $userAddress['id'],
                        'device_id' => null,
                        'location_name' => $name,
                        'updated_at' => now(), // Laravel helper for Carbon::now()
                    ];
                });
                // Insert new location data
                if ($locationData->isNotEmpty()) {
                    $this->model::insert($locationData->toArray());
                }
            }
        });

        // Check if the user has locations after the transaction
        return $this->model::where('user_id', $userAddress['user_id'])->exists();
    }



    /**
     * Destroy Location records from the database.
     *
     * This function uses the underlying model to Delete the Location entries.
     *
     * @param int $id
     * @return Bool
     */
    public function delete($id)
    {
        try {
            $device = $this->model::where('id', $id)->delete();
            if ($device) {
                return successMessage('Device Deleted !!');
            } else {
                return errorMessage();
            }
        } catch (ModelNotFoundException $e) {
            return exceptionMessage($e->getMessage());
        }
    }

    /**
     * Get Location names by Users (Customer id).
     *
     * This function uses the underlying model to fetching locations name from database .
     *
     * @param int $id
     * @return Json
     */
    public function getLocationNamesAsPerCustomer($customer_id)
    {
        $locationName = $this->model::where('user_id', $customer_id)->get();
        if ($locationName) {
            return response()->json(['locations' => $locationName]);
        } else {
            return response()->json(['locations' => []], statusMessage(404));
        }
    }

    public function getLocationNameCount()
    {
        $model = $this->model;
        if (isSuperAdmin()) {
            return $model::count();
        } elseif (isManager()) {
        } else {
            // return $model::whereHas('roles', function ($query) {
            //     $query->whereIn('name', ['Customer']);
            // })->count();
            return 0;
        }
    }
}
