<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AddonSetting;
use App\Services\AddonRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use ZipArchive;

class AddonController extends Controller
{
    // list addons
    public function index(Request $request)
    {
        // get all addons with pagination
        $addons = Addon::orderBy('id', 'DESC');
        $addons = $addons->paginate(config('app.pagination'))->withQueryString();

        // get available addons from config/addon.php and installed addons from database and pass to view
        $availableAddons = config('addon', []);
        $installedAddonIds = Addon::pluck('addon_id')->map(fn($id) => (string) $id)->toArray();

        return view('admin.addon.index', [
            'pageTitle' => __('Addons'),
            'addons' => $addons,
            'availableAddons' => $availableAddons,
            'installedAddonIds' => $installedAddonIds
        ]);
    }
}
