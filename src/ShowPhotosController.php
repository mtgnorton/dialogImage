<?php
namespace Mtg\ShowImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowPhotosController extends Controller {

    protected $service;

    public function __construct(ShowImagesService $service){

        $this->service = $service;
        $this->service->js();
    }

    public function index(){


        return view('showphotos',compact('files'));

    }
    public function getImages(Request $request){

        $page = $request->page ?? 2;

        $pageNumber = $request->number ?? 12;

        $allNumber = $this->service->getImagesNumber();

        $images = $this->service->getImages()->forPage($page,$pageNumber)->values();

        return compact('allNumber','images');

    }
    
    public function deleteImages(Request $request){

      return  $this->service->deleteImages($request->images);

    }

}