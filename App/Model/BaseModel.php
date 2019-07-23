<?php
/**
 * Description: 基础Model类
 * Created by Dong.cx
 * DateTime: 2019-07-22 16:45
 * @version V4.0.1
 */

namespace App\Model;


use EasySwoole\Mysqli\Mysqli;

abstract class BaseModel
{
	private $db;

	protected $tableName;
	protected $pk;

	public function __construct(Mysqli $db)
	{
		$this->db = $db;
	}

	protected function db(): Mysqli
	{
		return $this->db;
	}
}