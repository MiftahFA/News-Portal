<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminContactUpdateRequest;
use App\Models\Contact;
use App\Models\Language;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('permission:contact index,admin', only: ['index']),
      new Middleware('permission:contact update,admin', only: ['update']),
    ];
  }

  public function index()
  {
    $languages = Language::all();
    return view('admin.contact-page.index', compact('languages'));
  }

  public function update(AdminContactUpdateRequest $request)
  {
    Contact::updateOrCreate(
      ['language' => $request->language],
      [
        'address' => $request->address,
        'phone' => $request->phone,
        'email' => $request->email
      ]
    );

    toast(__('admin.Updated Successfully'), 'success');
    return redirect()->back();
  }
}
