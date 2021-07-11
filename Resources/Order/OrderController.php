<?php
namespace Resources\Order;

use Rakit\Validation\Validator;

class OrderController extends \Core\Controller
{

	public function insertOrder()
    {
    	
        $validator = new Validator;
        $orderModel = new OrderModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'customer_id'      => 'required|numeric',
            'restaurant_id'    => 'required|numeric',
            'table_number'     => 'required|numeric',
            'table_id'         => 'required|numeric'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $order = $orderModel->create($input);
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

    public function approveOrder(){
        
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
      
        $order = $orderModel->approve($input);
        if($order)
        {

            $this->response->renderOk($this->response::HTTP_CREATED, 'Successfully approved');
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }

    public function cancelOrder(){
        
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
      
        $order = $orderModel->cancel($input);
        if($order)
        {

            $this->response->renderOk($this->response::HTTP_CREATED, 'Successfully cancelled');
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }
    
    public function getRestaurantNonFinishedOrders($_id){
        $model = new OrderModel();
        $selector = 'orders.restaurant_id' ;
        $orders = $model->getNonFinished($selector, $_id);
        if($orders)  $this->response->renderOk($this->response::HTTP_OK, $orders);
    }

    public function getCustomerNonFinishedOrders($_id){
        $model = new OrderModel();
        $selector = 'orders.customer_id' ;
        $orders = $model->getNonFinished($selector, $_id);
        if($orders)  $this->response->renderOk($this->response::HTTP_OK, $orders);
    }
}