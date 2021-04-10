<?php

namespace Resources\Search;
use Rakit\Validation\Validator;

class SearchController extends \Core\Controller
{
	public function index()
	{
		$model = new SearchModel();
		$validator = new Validator;
        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'restaurant_name'   => 'required',
            "categories"        => "required"
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }

        $input["categories"] = explode(',', $input["categories"]);

        $search = $model->searchBy($input);
		if($search)
		{
			$this->response->renderOk($this->response::HTTP_OK, $search);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find search.");
		}
	}

}