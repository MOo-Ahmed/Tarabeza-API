<?php
namespace Resources\Menu;

use Rakit\Validation\Validator;

class MenuController extends \Core\Controller
{
	/*
		This function takes the id of the restaurant and the id of the category of the menu item, 
		then it'll call a recommender engine to return a set of recommended menu items 
	*/
	public function recommendItems()
    {
    	$this->response->renderOk($this->response::HTTP_OK, "Here are the recommendations");
    }
	
	public function index($_id){
		$model = new MenuModel();
		$menu["restaurant_id"] = $_id;
		$menu["items"] = $model->findItemsByRestaurantId($_id);
		
		if($menu)
		{
			$this->response->renderOk($this->response::HTTP_OK, $menu);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "No menu items found.");
		}
		
	}
	
}