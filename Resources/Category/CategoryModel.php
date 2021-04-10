<?php

namespace Resources\Category;

class CategoryModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "categories";
	protected $hidden = array("is_item");
	
	public function findAll($_isItem)
	{
		$res = CategoryModel::where("is_item", $_isItem)->get();
		return $res;
	}
}