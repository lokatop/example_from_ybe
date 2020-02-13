<?php

namespace App\Http\Controllers\Blog\admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    public function index()
    {
        //$paginator = BlogCategory::paginate(15);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return view('blog.admin.categories.index',compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = BlogCategory::make();
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.categories.edit',
            compact('item','categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        if (empty($data['slug'])){
            $data['slug'] = Str::slug($data['title']);
        }
        //первый способ создания
//        $item = new BlogCategory($data);
//        $item->save();

        //Второй способ создания объеута и добавления в БД
        $item = BlogCategory::create($data);

        if ($item){
            return redirect()->route('blog.admin.categories.edit',[$item->id])
                ->with(['success' => 'Успешно сохранено']);
        }else{
            return back()->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param BlogCategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$item = BlogCategory::find($id);
        //$categoryList = BlogCategory::all();
        $item = $this->blogCategoryRepository->getEdit($id);
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.categories.edit', compact('item','categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogCategoryUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {/*
        $rules = [
            'title'         => 'required|min:5|max:200'  ,
            'slug'          => 'max:200'  ,
            'parent_id'     => 'string|max:500|min:3'  ,
            'description'   => 'required|integer|exists:blog_categories,id'  ,
        ];
        //$validatedData = $this->validate($request,$rules);
        //$validatedData = $request->validate($request,$rules);
        $validator = \Validator::make($request->all(),$rules);
        $validatedData[] = $validator->validate();
        $validatedData[] = $validator->failed();
        $validatedData[] = $validator->errors();
        $validatedData[] = $validator->fails();
        */


        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)){
            return back()
                ->withErrors(['msg'=>"Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        /*
         *
         * Ушло в observer

        if (empty($data['slug'])){
            $data['slug'] = Str::slug($data['title']);
        }
        */

        $result = $item
            ->fill($data)
            ->save();

        if ($result){
            return redirect()
                ->route('blog.admin.categories.edit',$item->id)
                ->with(['success'=>'успешно сохранено']);
        }else{
            return back()
                ->withErrors(['msg'=>"Ошибка сохранения"])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd(__METHOD__);
    }
}
