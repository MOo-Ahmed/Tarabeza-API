<?php

namespace Resources\Category;
use Rakit\Validation\Validator;

class CategoryController extends \Core\Controller
{
	public function showRestaurantCategories()
	{
		$model = new CategoryModel();
		$isItem = False;
		$restaurantCategories = $model->findAll($isItem);

		if($restaurantCategories)
		{
			$this->response->renderOk($this->response::HTTP_OK, $restaurantCategories);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find categories.");
		}
	}

	public function showItemCategories()
	{
		$model = new CategoryModel();
		$isItem = True;
		$itemCategories = $model->findAll($isItem);

		if($itemCategories)
		{
			$this->response->renderOk($this->response::HTTP_OK, $itemCategories);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find categories.");
		}
	}
}