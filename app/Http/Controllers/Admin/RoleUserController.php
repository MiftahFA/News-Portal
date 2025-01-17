<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoleUserStoreRequest;
use App\Http\Requests\AdminRoleUserUpdateRequest;
use App\Mail\RoleUserCreateMail;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleUserController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('permission:access management index,admin', only: ['index']),
      new Middleware('permission:access management create,admin', only: ['create', 'store']),
      new Middleware('permission:access management update,admin', only: ['edit', 'update']),
      new Middleware('permission:access management delete,admin', only: ['destroy']),
    ];
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $admins = Admin::all();
    return view('admin.role-user.index', compact('admins'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $roles = Role::all();
    return view('admin.role-user.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(AdminRoleUserStoreRequest $request)
  {
    try {
      $user = new Admin();
      $user->image = '';
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = bcrypt($request->password);
      $user->status = 1;
      $user->save();

      /** assign the role to user */
      $user->assignRole($request->role);

      /** send mail to the user */
      Mail::to($request->email)->send(new RoleUserCreateMail($request->email, $request->password));

      toast(__('admin.Created Successfully'), 'success');
      return redirect()->route('admin.role-users.index');
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $user = Admin::findOrFail($id);
    $roles = Role::all();
    return view('admin.role-user.edit', compact('user', 'roles'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(AdminRoleUserUpdateRequest $request, string $id)
  {
    if ($request->has('password') && !empty($request->password)) {
      $request->validate([
        'password' => 'confirmed|min:6'
      ]);
    }

    $user = Admin::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->has('password') && !empty($request->password)) {
      $user->password = bcrypt($request->password);
    }

    $user->save();

    /** assign the role to user */
    $user->syncRoles($request->role);

    toast(__('admin.Updated Successfully'), 'success');
    return redirect()->route('admin.role-users.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $user = Admin::findOrFail($id);
    if ($user->getRoleNames()->first() === 'Super Admin') {
      return response(['status' => 'error', 'message' => __('admin.Can\'t Delete the Super User')]);
    }

    $user->delete();
    return response(['status' => 'success', 'message' => __('admin.Deleted Successfully')]);
  }
}
