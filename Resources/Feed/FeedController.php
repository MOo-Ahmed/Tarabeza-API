<?php

namespace Resources\Feed;

use Resources\User;

class FeedController extends \Core\Controller
{
	public function index()
	{
		$model = new FeedModel();
		$data = [];

		$data["recent_restaurants"] = $model->recentRestaurants(6);
		$data["recent_items"] = $model->recentItems(6);

		if($data)
		{
			$this->response->renderOk($this->response::HTTP_OK, $data);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find restaurants.");
		}
	}

	public function show($_id)
	{
		$model = new FeedModel();
		$data = [];

		$data["recent_restaurants"] = $model->recentRestaurants(6);
		$data["recent_items"] = $model->recentItems(6);

		$json = json_decode(file_get_contents($GLOBALS["app"]["recommendation_systems"]["restaurant_url"] . $_id));

		$data["recommended_restaurants"] = $json->data;

		if($data)
		{
			$this->response->renderOk($this->response::HTTP_OK, $data);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find restaurants.");
		}
	}

}