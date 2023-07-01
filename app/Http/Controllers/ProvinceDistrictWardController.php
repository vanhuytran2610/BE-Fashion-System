<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class ProvinceDistrictWardController extends Controller {
    public function getProvince() {
        $data['province'] = Province::get(['name', 'code']);
        return response()->json($data);
    }

    public function getDistrict($province_code) {
        $data['district'] = District::where("province_code", $province_code)->get(['name', 'code']);
        if (!$data['district']) {
            return response()->json([
                "status" => 404,
                "message" => "District not found"
            ]);
        }
        return response()->json($data);
    }

    public function getWard($district_code) {
        $data['ward'] = Ward::where("district_code", $district_code)->get(['name', 'code']);
        if (!$data['ward']) {
            return response()->json([
                "status" => 404,
                "message" => "Ward not found"
            ]);
        }
        return response()->json($data);
    }
}