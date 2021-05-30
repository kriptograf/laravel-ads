<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\RejectRequest;
use App\Http\Requests\Cabinet\Banner\CreateRequest;
use App\Http\Requests\Cabinet\Banner\EditRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Region;
use App\Services\Banner\BannerService;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $banners = Banner::orderByDesc('id')->paginate(20);

        $statuses = Banner::statusesList();

        return view('admin.banners.index', [
            'banners' => $banners,
            'statuses' => $statuses,
        ]);
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', ['banner' => $banner]);
    }

    public function update(Banner $banner)
    {
        if (!$banner->canBeChanged()) {
            return redirect()->route('admin.banners.show', $banner)->with('error', __('Unable to edit banner'));
        }

        $formats = Banner::formatsList();

        return view('admin.banners.edit', [
            'banner' => $banner,
            'formats' => $formats,
        ]);
    }

    public function edit(EditRequest $request, Banner $banner)
    {
        try{
            $this->service->edit($banner->id, $request);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }

    public function moderate(Banner $banner)
    {
        try{
            $this->service->moderate($banner->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }

    public function rejectForm(Banner $banner)
    {
        return view('admin.banners.edit.reject', ['banner' => $banner]);
    }

    public function reject(RejectRequest $request, Banner $banner)
    {
        try{
            $this->service->reject($banner->id, $request);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }

    public function pay(Banner $banner)
    {
        try{
            $this->service->pay($banner->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }

    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('admin.banners.create.category', ['categories' => $categories]);
    }

    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.banners.create.region', [
            'category' => $category,
            'region' => $region,
            'regions' => $regions,
        ]);
    }

    public function banner(Category $category, Region $region = null)
    {
        $formats = Banner::formatsList();

        return view('cabinet.banners.create.banner', [
            'category' => $category,
            'region' => $region,
            'formats' => $formats,
        ]);
    }

    public function store(CreateRequest $request, Category $category, Region $region = null)
    {
        try{
            $banner = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function destroy(Banner $banner)
    {
        try{
            $this->service->removeByAdmin($banner->id);
        }catch(\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }
}
