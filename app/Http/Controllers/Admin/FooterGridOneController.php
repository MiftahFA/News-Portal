<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterGridOneRequest;
use App\Models\FooterGridOne;
use App\Models\FooterTitle;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FooterGridOneController extends Controller implements HasMiddleware
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
    return view('admin.footer-grid-one.index', compact('languages'));
  }

  public function create()
  {
    $languages = Language::all();
    return view('admin.footer-grid-one.create', compact('languages'));
  }

  public function store(FooterGridOneRequest $request)
  {
    $footer = new FooterGridOne();
    $footer->language = $request->language;
    $footer->name = $request->name;
    $footer->url = $request->url;
    $footer->status = $request->status;
    $footer->save();

    toast(__('admin.Created Successfully'), 'success');
    return redirect()->route('admin.footer-grid-one.index');
  }

  public function edit(string $id)
  {
    $languages = Language::all();
    $footer = FooterGridOne::findOrFail($id);
    return view('admin.footer-grid-one.edit', compact('footer', 'languages'));
  }

  public function update(FooterGridOneRequest $request, string $id)
  {
    $footer = FooterGridOne::findOrFail($id);
    $footer->language = $request->language;
    $footer->name = $request->name;
    $footer->url = $request->url;
    $footer->status = $request->status;
    $footer->save();

    toast(__('admin.Updated Successfully'), 'success');
    return redirect()->route('admin.footer-grid-one.index');
  }

  public function destroy(string $id)
  {
    FooterGridOne::findOrFail($id)->delete();
    return response(['status' => 'success', 'message' => __('admin.Deleted Successfully')]);
  }

  public function handleTitle(Request $request)
  {
    $request->validate([
      'title' => ['required', 'max:255']
    ]);

    FooterTitle::updateOrCreate(
      [
        'key' => 'grid_one_title',
        'language' => $request->language
      ],
      [
        'value' => $request->title
      ]
    );

    toast(__('admin.Updated Successfully'), 'success');
    return redirect()->back();
  }
}
