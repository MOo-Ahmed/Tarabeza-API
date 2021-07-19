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

	public function update($_id)
	{
		$validator = new Validator;

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'name'                => 'required|alpha_spaces',
            "description"            => "required|alpha_spaces",
            'price'      => 'required|numeric',
            "discount"             => "required|numeric",
            "is_available"        => "required|numeric|min:0|max:1"
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }
        
		$menuModel = new MenuModel();
		$menu = $menuModel->findById($_id);

		if($menu)
		{
			$isUpdated = $menuModel->updatea($_id, $input);
			if($isUpdated)
			{
				$this->response->renderOk($this->response::HTTP_OK, 
										  "Item data updated successfully");
			}
			else    
			{
				$this->response->renderFail($this->response::HTTP_BAD_REQUEST, 
											"Invalid update data provided.");
			}
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_BAD_REQUEST, 
										"Can't find the Item specified.");
		}
	}
	
}