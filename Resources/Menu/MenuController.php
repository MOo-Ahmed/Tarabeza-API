<?php
namespace Resources\Menu;

use Rakit\Validation\Validator;

class MenuController extends \Core\Controller
{
	
	public function recommendItems($rest_id, $cat_id)
    {
		$data = [];
		$json = json_decode(file_get_contents($GLOBALS["app"]["recommendation_systems"]["menu_url"] . $rest_id . "/" . $cat_id));
		$data = $json->Data;

		if($data)
		{
			$this->response->renderOk($this->response::HTTP_OK, $data);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "No menu items found or the restaurant doesn't exist anymore.");
		}
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