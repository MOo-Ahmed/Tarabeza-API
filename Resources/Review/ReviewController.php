<?php
namespace Resources\Review;

use Rakit\Validation\Validator;

class ReviewController extends \Core\Controller
{
	public function postCustomerReview(){
        
        
        $validator = new Validator;
        $reviewModel = new ReviewModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'customer_id'           => 'required|numeric',
            "restaurant_id"         => "required|numeric",
            'comment'              	=> 'required|min:4|max:255',
            "rate"            	    => "required|numeric"
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $review = $reviewModel->insert($input);
        if($review)
        {

            $this->response->renderOk($this->response::HTTP_CREATED, 'Successfully added your review');
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
        
        
	}
	
}