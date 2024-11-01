<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SocialLinkController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('permission:footer index,admin', only: ['index']),
      new Middleware('permission:footer create,admin', only: ['create', 'store']),
      new Middleware('permission:footer update,admin', only: ['edit', 'update']),
      new Middleware('permission:footer delete,admin', only: ['destroy']),
    ];
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $socialLinks = SocialLink::all();
    return view('admin.social-link.index', compact('socialLinks'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.social-link.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(AdminSocialLinkRequest $request)
  {
    $social = new SocialLink();
    $social->icon = $request->icon;
    $social->url = $request->url;
    $social->status = $request->status;
    $social->save();

    toast(__('admin.Created Successfully'), 'success');
    return redirect()->route('admin.social-link.index');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $socialLink = SocialLink::findOrFail($id);
    return view('admin.social-link.edit', compact('socialLink'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(AdminSocialLinkRequest $request, string $id)
  {
    $social = SocialLink::findOrFail($id);
    $social->icon = $request->icon;
    $social->url = $request->url;
    $social->status = $request->status;
    $social->save();

    toast(__('admin.Updated Successfully'), 'success');

    return redirect()->route('admin.social-link.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    SocialLink::findOrFail($id)->delete();
    return response(['status' => 'success', 'message' => __('admin.Deleted Successfully')]);
  }
}
