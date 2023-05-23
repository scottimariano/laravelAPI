<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Discount;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiscountExport implements FromCollection, WithHeadings
{
    protected $nombre;
    protected $rentadora;
    protected $region;
    protected $awd_bcd;

    public function __construct($nombre, $rentadora, $region, $awd_bcd)
    {
        $this->nombre = $nombre;
        $this->rentadora = $rentadora;
        $this->region = $region;
        $this->awd_bcd = $awd_bcd;
    }

    public function collection()
    {
        $discounts = Discount::with(['accesstype','brand','region','discountRanges'])
            ->where('name', 'LIKE', '%' . $this->nombre . '%')
            ->whereHas('brand', function ($query) {
                $query->where('active', 1);
                if ($this->rentadora) {
                    $query->where('name', $this->rentadora);
                }
            })
            ->whereHas('region', function ($query) {
                if ($this->region) {
                    $query->where('name', 'LIKE', '%' . $this->region . '%');
                }
            })
            ->whereHas('discountRanges', function ($query) {
                if ($this->awd_bcd) {
                    $query->where('code', $this->awd_bcd);
                }
            })
            ->get();

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

            Log::info($filteredResponse);
            
            return $filteredResponse;
    }

    public function headings(): array
    {
        return [
            'Rentadora',
            'Region',
            'Nombre',
            'Tipo de Acceso',
            'Estado',
            'Periodo',
            'AWD/BCD',
            'Descuento GSA',
            'Descuento GSA',
            'Periodo de promoción',
            'Prioridad',
        ];
    }
}