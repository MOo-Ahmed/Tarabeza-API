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
	
}