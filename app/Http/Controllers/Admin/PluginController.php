<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePluginRequest;
use App\Http\Requests\UpdatePluginRequest;
use App\Models\Plugin;
use Illuminate\Http\Request;
use Str;

class PluginController extends Controller
{
    public function index(Request $request)
    {
        $plugins = Plugin::orderBy('id', 'DESC');

        $plugins = $plugins->paginate(config('app.pagination'))->withQueryString();

        $availablePlugins = config('plugins', []);
        $installedPluginIds = Plugin::pluck('product_id')->map(fn($id) => (string) $id)->toArray();

        return view('admin.plugin.index', [
            'pageTitle' => __('Plugins'),
            'plugins' => $plugins,
            'availablePlugins' => $availablePlugins,
            'installedPluginIds' => $installedPluginIds
        ]);
    }
}
