<?php

use App\Http\Resources\DeliveryCompanyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\DeliveryCompany;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/delivery-companies', function(){
    return new DeliveryCompanyResource(DeliveryCompany::all());
});

Route::get('/delivery-company/{companyId}/price/{usersKilo}', function ($companyId, $usersKilo){
    $deliveryCompany = DeliveryCompany::findOrFail($companyId);
    $deliveryCompanyResource = new DeliveryCompanyResource($deliveryCompany);

    return $deliveryCompanyResource->getPrice($usersKilo);
});

Route::post('/task2', function (Request $request){
    $str = $request->input('text');
    $tagValueDict = array();
    $tagDescriptionDict = array();
    $pattern = '/\[(\w+)\s+description=”([^”]+)”\](.*?)\[\/\1\]|\[(\w+)\](.*?)\[\/\4\]/s';
    preg_match_all($pattern, $str, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        if (!empty($match[1])) {
            $tagName = $match[1];
            $tagValue = $match[3];
            $tagDescription = $match[2];
        } else {
            $tagName = $match[4];
            $tagValue = $match[5];
            $tagDescription = null;
        }

        $tagValueDict[$tagName] = $tagValue;

        if (!empty($tagDescription)) {
            $tagDescriptionDict[$tagName] = $tagDescription;
        }
    }

    return response()->json(['arr1' => $tagValueDict, 'arr2' => $tagDescriptionDict]);
});
