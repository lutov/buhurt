<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 03.01.2020
 * Time: 13:35
 */

namespace App\Traits;

/**
 * Class SearchableTrait
 * @package App\Traits
 */
class SearchableTrait {

	public static function bootSearchable()
	{
		// Это облегчает переключение флага поиска.
		// Будет полезно позже при развертывании
		// новой поисковой системы в продакшене
		if (config('services.search.enabled')) {
			static::observe(ElasticsearchObserver::class);
		}
	}

	public function getSearchIndex()
	{
		return $this->getTable();
	}

	public function getSearchType()
	{
		if (property_exists($this, 'useSearchType')) {
			return $this->useSearchType;
		}
		return $this->getTable();
	}

	public function toSearchArray()
	{
		// Наличие пользовательского метода
		// преобразования модели в поисковый массив
		// позволит нам настраивать данные
		// которые будут доступны для поиска
		// по каждой модели.
		return $this->toArray();
	}

}