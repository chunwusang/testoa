<?php 
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	//这是自己写的
	

	public function showview($tpl)
	{
		return view($tpl);
	}
}

 ?>