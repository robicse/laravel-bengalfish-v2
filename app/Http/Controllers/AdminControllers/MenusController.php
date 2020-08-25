<?php

namespace App\Http\Controllers\AdminControllers;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Hash;
use App\Models\Core\Administrator;
use App\Models\Core\Menus;
use App\Models\Core\NewsCategory;
use App;
use Lang;
use Auth;
use Illuminate\Http\Request;

class MenusController extends Controller
{

	public function menus(Request $request){
		$title 			= array('pageTitle' => Lang::get("labels.menus"));
    $result = Menus::menus();
		return view("admin.menus.index",$title)->with('result', $result);

	}

	public function addmenus(Request $request){

		$title = array('pageTitle' => Lang::get("labels.addmenus"));
     $result = Menus::addmenus();
		return view("admin.menus.add", $title)->with('result', $result);

	}

	//addNewPage
	public function addnewmenu(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddMenu"));
      Menus::addnewmenu($request);
		$message = Lang::get("labels.MenuAddedMessage");
		return redirect()->back()->withErrors([$message]);

	}

	//editnew
	public function editmenu(Request $request,$id){

		$title = array('pageTitle' => Lang::get("labels.EditPage"));
    $result = Menus::editmenu($id);
		return view("admin.menus.edit", $title)->with('result', $result);

	}


	//updatePage
	public function updatemenu(Request $request){

       Menus::updatemenu($request);
		$message = Lang::get("labels.MenuUpdateMessage");
		return redirect()->back()->withErrors([$message]);

	}

	public function deletemenu($id){

			 Menus::deletemenu($id);
		$message = Lang::get("labels.MenuDeleteMessage");
		return redirect()->back()->withErrors([$message]);

	}


	//pageStatus
	public function pageStatus(Request $request){

        Pages::pageStatus($request);
    		return redirect()->back()->withErrors([Lang::get("labels.PageStatusMessage")]);

	}


	//listing web pages
	public function webpages(Request $request){

		$title 			= array('pageTitle' => Lang::get("labels.Pages"));
    $result = Pages::webpages($request);
		return view("admin.pages.webpages.index",$title)->with('result', $result);

	}

	public function addwebpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddPage"));
     $result = Pages::addwebpage($request);
		return view("admin.pages.webpages.add", $title)->with('result', $result);

	}

	//addNewPage
	public function addnewwebpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddPage"));
      Pages::addnewwebpage($request);
		$message = Lang::get("labels.PageAddedMessage");
		return redirect()->back()->withErrors([$message]);

	}

	//editnew
	public function editwebpage(Request $request){

		$title = array('pageTitle' => Lang::get("labels.EditPage"));
    $result = Pages::editwebpage($request);
		return view("admin.pages.webpages.edit", $title)->with('result', $result);

	}


	//updatePage
	public function updatewebpage(Request $request){

      Pages::updatewebpage($request);
		$message = Lang::get("labels.PageUpdateMessage");
		return redirect()->back()->withErrors([$message]);

	}


	//pageStatus
	public function pageWebStatus(Request $request){

        Pages::pageWebStatus($request);
			return redirect()->back()->withErrors([Lang::get("labels.PageStatusMessage")]);

	}


}
