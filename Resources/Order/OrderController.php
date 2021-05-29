<?php
namespace Resources\Order;

use Rakit\Validation\Validator;

class OrderController extends \Core\Controller
{

	public function create()
    {
    	
        $validator = new Validator;
        $orderModel = new OrderModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'customer_id'           => 'required|numeric',
            "restaurant_id"         => "required|numeric",
            'table_id'              => 'required|numeric'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $order = $orderModel->insertOrder($input);
        if($order)
        {

            $this->response->renderOk($this->response::HTTP_CREATED, $order);
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }
    
    public function finishOrder(){
        
        $validator = new Validator;
        $orderModel = new OrderModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'order_id'           => 'required|numeric'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $order = $orderModel->finish($input);
        if($order)
        {

            $this->response->renderOk($this->response::HTTP_CREATED, 'Successfully marked as finished');
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }

    public function confirmPayOrder(){
        
        $validator = new Validator;
        $orderModel = new OrderModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'order_id'           => 'required|numeric'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $order = $orderModel->pay($input);
        if($order)
        {

            $this->response->renderOk($this->response::HTTP_CREATED, 'Successfully marked as paid');
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }

    public function showCustomer($user_id){

    }

    public function showRestaurant($restaurant_id){

    }
	
}