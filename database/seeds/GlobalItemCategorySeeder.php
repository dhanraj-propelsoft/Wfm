<?php

use Illuminate\Database\Seeder;
use App\GlobalItemCategoryType;
use App\GlobalItemCategory;
use Carbon\Carbon;

class GlobalItemCategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		/*$goods = GlobalItemCategoryType::where('name', 'goods')->first()->id;
		$service = GlobalItemCategoryType::where('name', 'service')->first()->id;

		$goods_category = new GlobalItemCategory;
		$goods_category->name = "Goods";
		$goods_category->category_type_id = $goods;
		$goods_category->display_name = "Goods";
		$goods_category->save();

		$service_category = new GlobalItemCategory;
		$service_category->name = "Service";
		$service_category->category_type_id = $service;
		$service_category->display_name = "Service";
		$service_category->save();*/

	}
}
