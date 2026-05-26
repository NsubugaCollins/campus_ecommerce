<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json([
            'settings' => Setting::all()->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token']) as $key => $value) {
            if (is_string($key)) {
                Setting::set($key, is_array($value) ? json_encode($value) : (string) $value);
            }
        }

        return response()->json([
            'message' => 'Settings updated',
            'settings' => Setting::all()->pluck('value', 'key'),
        ]);
    }
}
