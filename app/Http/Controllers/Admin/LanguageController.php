<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLanguageStoreRequest;
use App\Http\Requests\AdminLanguageUpdateRequest;
use App\Models\Language;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LanguageController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('permission:languages index,admin', only: ['index']),
      new Middleware('permission:languages create,admin', only: ['create']),
      new Middleware('permission:languages update,admin', only: ['update']),
      new Middleware('permission:languages delete,admin', only: ['destroy']),
    ];
  }

  public function index()
  {
    $languages = Language::all();
    return view('admin.language.index', compact('languages'));
  }

  public function create()
  {
    return view('admin.language.create');
  }

  public function store(AdminLanguageStoreRequest $request)
  {
    $language = new Language();
    $language->name = $request->name;
    $language->lang = $request->lang;
    $language->slug = $request->slug;
    $language->default = $request->default;
    $language->status = $request->status;
    $language->save();

    toast(__('admin.Created Successfully'), 'success')->width('350');
    return redirect()->route('admin.language.index');
  }

  public function edit(string $id)
  {
    $language = Language::findOrFail($id);
    return view('admin.language.edit', compact('language'));
  }

  public function update(AdminLanguageUpdateRequest $request, string $id)
  {
    $language = Language::findOrFail($id);
    $language->name = $request->name;
    $language->lang = $request->lang;
    $language->slug = $request->slug;
    $language->default = $request->default;
    $language->status = $request->status;
    $language->save();

    toast(__('admin.Updated Successfully'), 'success')->width('350');
    return redirect()->route('admin.language.index');
  }

  public function destroy(string $id)
  {
    try {
      $language = Language::findOrFail($id);
      if ($language->lang === 'en') {
        return response(['status' => 'error', 'message' => __('admin.Can\'t Delete This One!')]);
      }
      $language->delete();
      return response(['status' => 'success', 'message' => __('admin.Deleted Successfully')]);
    } catch (\Throwable $th) {
      return response(['status' => 'error', 'message' => __('admin.Something went wrong!')]);
    }
  }
}
