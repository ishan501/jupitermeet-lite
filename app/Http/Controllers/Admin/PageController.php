<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public $restrictedSlugs = ['home', 'privacy-policy', 'terms-and-conditions', 'thank-you'];

    public function index(Request $request)
    {
        $pages = Page::orderBy('id', 'DESC');

        $isFiltered = false;

        // Setting array for filtering title and slug
        $filters = [
            'title' => $request->title,
            'slug' => $request->slug,
        ];

        // Filter title and slug
        foreach ($filters as $column => $value) {
            if ($value) {
                $pages->where($column, 'like', '%' . $value . '%');
                $isFiltered = true;
            }
        }

        $pages = $pages->paginate(config('app.pagination'))->withQueryString();

        return view('admin.page.index', [
            'pageTitle' => __('Pages'),
            'pages' => $pages,
            'filters' => $filters,
            'isFiltered' => $isFiltered,
        ]);
    }

    // Show create language form
    public function create(Request $request)
    {
        return view('admin.page.create', ['pageTitle' => __('Create Page')]);
    }

    // Store the newly created value in database
    public function store(CreatePageRequest $request)
    {
        $page = new Page();
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->footer = $request->footer ? 'yes' : 'no';
        $page->content = $request->content;
        $page->save();

        return redirect()->route('admin.page')->with('message', __('Page created Successfully.'));
    }

    // Show edit form for a particular page
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('admin.page.edit', [
            'pageTitle' => __('Edit Page'),
            'page' => $page,
            'restrictedSlugs' => $this->restrictedSlugs
        ]);
    }

    // Update page in database
    public function update(UpdatePageRequest $request, $id)
    {
        $page = Page::findOrFail($id);

        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->footer = $request->footer ? 'yes' : 'no';
        $page->content = $request->content;
        $page->save();

        return redirect()->route('admin.page')->with('message', __('Page updated Successfully.'));
    }

    // Soft delete page
    public function delete($id)
    {
        $page = Page::findOrFail($id);

        if (in_array($page->slug, $this->restrictedSlugs)) {
            return redirect()->route('admin.page')->with('message', __('This page can not be deleted.'));
        }

        $page->delete();

        return redirect()->route('admin.page')->with('message', __('Page Deleted Successfully.'));
    }


    public function show($id)
    {
        $page = Page::where('slug', $id)->firstOrFail();

        return view('pages', ['page' => $page, 'pageTitle' => $page->title]);
    }
}
