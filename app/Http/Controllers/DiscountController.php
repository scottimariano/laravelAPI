<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\DiscountRange;
use Illuminate\Http\Request;
use App\Http\Requests\DiscountRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App\Exports\DiscountExport;
use Maatwebsite\Excel\Facades\Excel;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        $discounts = Discount::with(['accesstype','brand','region','discountRanges'])
            ->where('name', 'LIKE', '%' . $request->query('nombre') . '%')
            ->whereHas('brand', function ($query) use ($request){
                $query->where('active', 1);
                if ($request->query('rentadora')) {
                    $query->where('name', $request->query('rentadora'));
                }
            })
            ->whereHas('region', function ($query) use ($request) {
                if ($request->query('region')) {
                    $query->where('name', 'LIKE', '%' . $request->query('region') . '%');
                }
            })
            ->whereHas('discountRanges', function ($query) use ($request) {
                if ($request->query('AWD/BCD')) {
                    $query->where('code', $request->query('AWD/BCD'));
                }
            })
            ->get();

            if ($discounts->isEmpty()) {
                return response()->json(['message' => 'No records found'], 404);
            }


        $filteredResponse = collect($discounts)->map(function ($item) {
            $discountRangePeriods = [];
            foreach ($item['discountRanges'] as $discountRange) {
                $period = "{$discountRange['from_days']} - {$discountRange['to_days']}";
                array_push($discountRangePeriods, $period);
            }

            $discountRangeCode = [];
            foreach ($item['discountRanges'] as $discountRange) {
                array_push($discountRangeCode, $discountRange['code']);
            }

            $discountRangeDiscount = [];
            foreach ($item['discountRanges'] as $discountRange) {
                array_push($discountRangeDiscount, $discountRange['discount']);
            }

            return [
                'Rentadora' => $item['brand']['name'],
                'Region' => $item['region']['name'],
                'Nombre' => $item['name'],
                'Tipo de Acceso' => $item['accesstype']['name'],
                'Estado' => $item['active'] ? "activo" : "inactivo",
                'Periodo' => $discountRangePeriods,
                'AWD/BCD' => $discountRangeCode,
                'Descuento GSA' => $discountRangeDiscount,
                'Descuento GSA' => $discountRangeDiscount,
                'Periodo de promoción' => "{$item['start_date']} - {$item['end_date']}",
                'Prioridad' => $item['priority']
            ];
        });

        $perPage = 5;
        $currentPage = request()->query('page', 1);
        $pagedData = $filteredResponse->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator(
            $pagedData,
            $filteredResponse->count(),
            $perPage,
            $currentPage,
            ['path' => '/']
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $discount = new Discount();
                $discount->fill($request->except(['start_date', 'end_date']));
                $discount->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
                $discount->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);

                if ($discount->save()) {
                    foreach ($request->discount_ranges as $discountRange) {
        
                        $newDiscountRange = new DiscountRange();
                        $newDiscountRange->fill(Arr::except($discountRange, ['discount_Id']));
                        $newDiscountRange->discount_Id = $discount->id;
                        $newDiscountRange->save();
                    }
                }

                $discount->load('discountRanges');

                return response()->json($discount, 201);

            } catch (Exception $e) {
                $errorMsg = 'Failed to save the record in the database. Please try again.';
                return response()->json($errorMsg, 422);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $discount = Discount::with('discountRanges')->findOrFail($id);
    
        return response()->json($discount, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountRequest $request, string $id)
    {
        $discount = Discount::findOrFail($id);

        $data = ['discount' => null, 'discount_ranges' => []];
        
        return DB::transaction(function () use ($discount, $request, $data) {

            try {
                $discount->fill($request->except(['start_date', 'end_date']));
                if ($request->has('start_date')) {
                    $discount->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
                }
                if ($request->has('end_date')) {
                    $discount->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
                }

                if ($discount->isDirty()) {
                    $discount->save();
                    $data['discount'] = $discount;
                }
                
                if ($request->has('discount_ranges')) {
                    foreach ($request->discount_ranges as $discountRange) {
                        $discountRangeToUpdate = DiscountRange::findOrFail($discountRange['id']);
                        $discountRangeToUpdate->fill(Arr::except($discountRange, ['discount_Id']));
                
                        if ($discountRangeToUpdate->isDirty()) {
                            $discountRangeToUpdate->save();
                            array_push($data['discount_ranges'], $discountRangeToUpdate);
                        }
                    }
                }

                return response()->json($data, 200);

            } catch (Exception $e) {
                $errorMsg = 'Failed to update the record in the database. Please try again.';
                return response()->json($errorMsg, 422);
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
    
        return response()->json([
            'message' => 'Discount deleted successfully'
        ], 200);
    }
    
    /**
     * Restore the specified resource from deletion.
     */
    public function restore(string $id)
    {
        $discount = Discount::withTrashed()->findOrFail($id);
    
        if ($discount->trashed()) {
            $discount->restore();
    
            return response()->json([
                'message' => 'Discount restored successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Discount is not deleted'
            ], 200);
        }
    }

    /**
     * Downlod a resource list.
     */
    public function downloadCSV(Request $request)
    {
        $nombre = $request->query('nombre');
        $rentadora = $request->query('rentadora');
        $region = $request->query('region');
        $awd_bcd = $request->query('AWD/BCD');

        return Excel::download(new DiscountExport($nombre, $rentadora, $region, $awd_bcd), 'descuentos.csv');
    }

}
