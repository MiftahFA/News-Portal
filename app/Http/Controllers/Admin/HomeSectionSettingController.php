<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminHomeSectionSettingUpdateRequest;
use App\Models\HomeSectionSetting;
use App\Models\Language;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HomeSectionSettingController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('permission:home section index,admin', only: ['index']),
      new Middleware('permission:home section update,admin', only: ['update'])
    ];
  }

  public function index()
  {
    $languages = Language::all();
    return view('admin.home-section-setting.index', compact('languages'));
  }

  public function update(AdminHomeSectionSettingUpdateRequest $request)
  {
    HomeSectionSetting::updateOrCreate(
      ['language' => $request->language],
      [
        'category_section_one' => $request->category_section_one,
        'category_section_two' => $request->category_section_two,
        'category_section_three' => $request->category_section_three,
        'category_section_four' => $request->category_section_four,
      ]
    );

    toast(__('admin.Updated Successfully'), 'success');
    return redirect()->back();
  }
}
