<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterInfo;
use App\Models\Language;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FooterInfoController extends Controller implements HasMiddleware
{
    use FileUploadTrait;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:footer index,admin', only: ['index']),
            new Middleware('permission:footer create,admin', only: ['store'])
        ];
    }

    public function index()
    {
        $languages = Language::all();
        return view('admin.footer-info.index', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|max:3000',
            'description' => 'required|max:300',
            'copyright' => 'required|max:255'
        ]);

        $footerInfo = FooterInfo::where('language', $request->language)->first();
        $imagePath = $this->handleFileUpload($request, 'logo', !empty($footerInfo) ? $footerInfo->logo : '');

        FooterInfo::updateOrCreate(
            ['language' => $request->language],
            [
                'logo' => !empty($imagePath) ? $imagePath : $footerInfo->logo,
                'description' => $request->description,
                'copyright' => $request->copyright
            ]
        );

        toast(__('Updated Successfully!'), 'success');
        return redirect()->back();
    }
}
