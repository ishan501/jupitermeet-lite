<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::orderBy('id', 'DESC');

        $isFiltered = false;

        // Set filter array for code, name and direction
        $filters = [
            'code' => $request->code,
            'name' => $request->name,
            'direction' => $request->direction,
        ];

        // Filter languages by status
        $status = $request->status;
        if ($status) {
            $languages->where('status', $status);
            $isFiltered = true;
        }

        // Filter by code , name and firection
        foreach ($filters as $column => $value) {
            if ($value) {
                $languages->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $languages = $languages->paginate(config('app.pagination'))->withQueryString();

        return view('admin.language.index', [
            'pageTitle' => __('Languages'),
            'languages' => $languages,
            'filters' => $filters,
            'status' => $status,
            'isFiltered' => $isFiltered,
        ]);
    }
}
