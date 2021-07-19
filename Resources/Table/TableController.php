<?php
namespace Resources\Table;

use Rakit\Validation\Validator;

class TableController extends \Core\Controller
{
    public function insertTable()
    {
        $validator = new Validator;
        $tableModel = new TableModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'number'      => 'required|numeric',
            'restaurant_id'    => 'required|numeric',
            'no_of_chairs'     => 'required|numeric'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $table = $tableModel->create($input);
        if($table)
        {
            $this->response->renderOk($this->response::HTTP_CREATED, $table);
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }

	public function index($_id)
	{
      $model = new TableModel();
      $tables = $model->getAll($_id);
      if($tables)  $this->response->renderOk($this->response::HTTP_OK, $tables);
    }

    public function getAvailable()
	{
        $validator = new Validator;        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'restaurant_id'    => 'required|numeric',
            'time'             => 'required'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }

        $model = new TableModel();
        $tables = $model->getAvailable($input);
        if($tables)  
        {
            $this->response->renderOk($this->response::HTTP_OK, $tables);
        }
        else{
            $this->response->renderFail($this->response::HTTP_NOT_FOUND, "No available tables found.");
        }
    }

    public function release($restaurant_id, $table_number){
        $model = new TableModel();
        $result = $model->toggle($restaurant_id, $table_number, 0);
        if($result)  $this->response->renderOk($this->response::HTTP_OK, $result);
    }
        
    public function hold($restaurant_id, $table_number){
        $model = new TableModel();
        $result = $model->toggle((int)$restaurant_id, (int)$table_number, 1);
        if($result)  $this->response->renderOk($this->response::HTTP_OK, $result);
    }
	
}