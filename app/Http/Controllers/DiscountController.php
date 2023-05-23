<?php

namespace App\Http\Controllers;

use App\Models\AccessType;
use App\Models\Brand;
use App\Models\Discount;
use App\Models\DiscountRange;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return response()->json([
            "ESTAMOSSS"
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //validaciones!!
        Log::info($request);
        Log::info("request");
        

        $discount = new Discount();
        $discount->name  = $request->name;
        $discount->active  = $request->active;
        $discount->brand_id  = Brand::findOrFail($request->brandId)->id;
        $discount->access_type_code = AccessType::where("code", $request->accessType)->first()->value('code');
        $discount->priority = $request->priority;
        $discount->region_id = Region::findOrFail($request->regionId)->id;
        $discount->start_date = Carbon::createFromFormat('d/m/Y', $request->startDate);
        $discount->end_date = Carbon::createFromFormat('d/m/Y', $request->endDate);
        
        $discount->save();

        // Create a loop that iterates through the discountRanges array
        foreach ($request->discountRanges as $discountRange) {

            // Create a new DiscountRange model
            $newDiscountRange = new DiscountRange();

            // Set the model properties
            $newDiscountRange->from_days = $discountRange['fromDays'];
            $newDiscountRange->to_days = $discountRange['toDays'];
            $newDiscountRange->discount = $discountRange['discount'];
            $newDiscountRange->code = $discountRange['code'];
            $newDiscountRange->discount_Id = $discount->id;
            // Save the model to the database
            $newDiscountRange->save();
        }


        return $discount;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
