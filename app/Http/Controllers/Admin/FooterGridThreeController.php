<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterGridOneRequest;
use App\Models\FooterGridThree;
use App\Models\FooterTitle;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FooterGridThreeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:footer index,admin', only: ['index']),
            new Middleware('permission:footer create,admin', only: ['create', 'store']),
            new Middleware('permission:footer update,admin', only: ['edit', 'update', 'handleTitle']),
            new Middleware('permission:footer delete,admin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $languages = Language::all();
        return view('admin.footer-grid-three.index', compact('languages'));
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.footer-grid-three.create', compact('languages'));
    }

    public function store(FooterGridOneRequest $request)
    {
        $footer = new FooterGridThree();
        $footer->language = $request->language;
        $footer->name = $request->name;
        $footer->url = $request->url;
        $footer->status = $request->status;
        $footer->save();

        toast(__('Created Successfully!'), 'success');
        return redirect()->route('admin.footer-grid-three.index');
    }

    public function edit(string $id)
    {
        $languages = Language::all();
        $footer = FooterGridThree::findOrFail($id);
        return view('admin.footer-grid-three.edit', compact('footer', 'languages'));
    }

    public function update(FooterGridOneRequest $request, string $id)
    {
        $footer = FooterGridThree::findOrFail($id);
        $footer->language = $request->language;
        $footer->name = $request->name;
        $footer->url = $request->url;
        $footer->status = $request->status;
        $footer->save();

        toast(__('Updated Successfully!'), 'success');
        return redirect()->route('admin.footer-grid-three.index');
    }

    public function destroy(string $id)
    {
        FooterGridThree::findOrFail($id)->delete();
        return response(['status' => 'success', 'message' => __('Deleted Successfully')]);
    }

    public function handleTitle(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255']
        ]);

        FooterTitle::updateOrCreate(
            [
                'key' => 'grid_three_title',
                'language' => $request->language
            ],
            [
                'value' => $request->title
            ]
        );

        toast(__('Updated Successfully'), 'success');
        return redirect()->back();
    }
}
