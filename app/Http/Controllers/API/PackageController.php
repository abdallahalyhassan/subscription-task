<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::where("status", true)->get();
        //    dd($packages);

        return response()->json([
            'success' => true,
            'data' => PackageResource::collection($packages),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
        try {

            $package = Package::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Package created successfully',
                'data' => new PackageResource($package),
            ], 201);

        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new PackageResource($package),
            ]);


        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        try {
            $package->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Package updated successfully',
                'data' => new PackageResource($package),
            ]);


        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        //
    }
}
