<?php
namespace Resources\Table;

use Rakit\Validation\Validator;

class TableController extends \Core\Controller
{
	public function index($_id)
	{
      $model = new TableModel();
      $tables = $model->getAllTables($_id);
      if($tables)  $this->response->renderOk($this->response::HTTP_OK, $tables);
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