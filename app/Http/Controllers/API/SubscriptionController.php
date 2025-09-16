<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\showSubscriptionToUserRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionStatusRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request)
    {
        try {
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $package = Package::findOrFail($request->package_id);
            // $expireAt = now()->addMonths($package->duration);
            // dd($expireAt);
            $activeSubscription = Subscription::where('user_id', Auth::id())
                ->where('package_id', $package->id)
                ->where('status', 'active') 
                ->where('expire_at', '>', now()) 
                ->exists();

            if ($activeSubscription ) {
                return response()->json([
                    'success' => false,
                    'massage' => "You already have an active subscription to this package",
                ], 409);
            }
            $subscription = Subscription::create([
                'user_id' => Auth::id(),
                'package_id' => $package->id,
                'paid_amount' => $request->paid_amount,
                'screenshot' => $path,
                "expire_at" => now()->addMonths($package->duration)
            ]);
            // dd($expireAt->toDateTimeString(), $subscription->expire_at);

            return response()->json([
                'success' => true,
                "message" => "you subscribed successfully",
                'data' => new SubscriptionResource($subscription),
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

    }



    public function update(UpdateSubscriptionRequest $request, $id)
    {
        try {

            $subscription = Subscription::findOrFail($id);
            // Update paid_amount


            if ($subscription->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this subscription',
                ], 403);
            }
            if ($request->has('paid_amount')) {
                $subscription->paid_amount = $request->paid_amount;
            }

            // Update screenshot
            if ($request->hasFile('screenshot')) {
                //  remove old screenshot
                if ($subscription->screenshot && Storage::disk('public')->exists($subscription->screenshot)) {
                    Storage::disk('public')->delete($subscription->screenshot);
                }

                $path = $request->file('screenshot')->store('screenshots', 'public');
                $subscription->screenshot = $path;
            }

            // Update status
            // if ($request->status) {
            //     $subscription->status = $request->status;
            // }

            $subscription->save();

            return response()->json([
                'success' => true,
                'message' => 'Subscription updated successfully',
                'data' => new SubscriptionResource($subscription),
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getpandingsubscription()
    {

        try {
            if (Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to get this subscription',
                ], 403);
            }
            $subscription = Subscription::where("status", 'pending')->get();

            return response()->json([
                'success' => true,
                'message' => 'Subscription pending geted successfully',
                'data' => SubscriptionResource::collection($subscription),
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updatestatus(UpdateSubscriptionStatusRequest $request, $id)
    {

        try {

            $subscription = Subscription::findOrFail($id);
            $subscription->status = $request->status;
            $subscription->save();

            return response()->json([
                'success' => true,
                'message' => 'Subscription updated successfully',
                'data' => new SubscriptionResource($subscription),
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function showsubscripedpackage(showSubscriptionToUserRequest $request)
    {
        // dd(123);

        try {

            $subscription = Subscription::where("user_id", Auth::id())->with('package')->get();

            return response()->json([
                'success' => true,
                'message' => 'Subscription geted successfully',
                'data' => SubscriptionResource::collection($subscription),
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,

                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function usersByPackage($packageName)
    {
        try {

            $package = Package::where('name', $packageName)->firstOrFail();
            // $package = Package::all();
            // dd($package);

            $subscriptions = $package->subscriptions()->with('user')->get();

            return response()->json([
                'success' => true,
                'message' => "Subscribed users for package: {$packageName}",
                'data' => SubscriptionResource::collection($subscriptions),
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
