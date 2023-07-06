<?php

namespace App\Http\Resources;

use App\Models\Proposition;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allPropositions = Proposition::all();
        $companyPropositions = [];
        $result = [];
        foreach ($this->resource as $company){
            foreach ($allPropositions as $proposition) {
                if($proposition->delivery_company_id == $company->id){
                    $companyPropositions[] = $proposition;
                }
            }
            $result[] = [
                'id' => $company->id,
                'name' => $company->name,
                'propositions' => $companyPropositions,
                'created_at' => $company->created_at,
                'updated_at' => $company->updated_at
            ];
            $companyPropositions = [];
        }
        return [$result];
    }
    public function getPrice(Int $kilo)
    {
        $allPropositions = Proposition::all();
        $company = $this->resource;
        foreach ($allPropositions as $proposition) {
            if($proposition->delivery_company_id == $company->id){
                if($proposition->type == "ranged" || $proposition->type == "range-fixed"){
                    $multiplier = 1;
                    if($proposition->type == "range-fixed"){
                        $multiplier = $kilo;
                    }
                    if(isset($proposition->min) && isset($proposition->max)){
                        if($kilo > $proposition->min && $kilo <= $proposition->max){
                            return $proposition->price * $multiplier;
                        }
                    } elseif(isset($proposition->min)){
                        if($kilo > $proposition->min){
                            return $proposition->price * $multiplier;
                        }
                    }elseif(isset($proposition->max)){
                        if($kilo <= $proposition->max){
                            return $proposition->price * $multiplier;
                        }
                    }
                } elseif ($proposition->type == "fixed"){
                    return $proposition->price * $kilo;
                }
            }
        }
    }
}
