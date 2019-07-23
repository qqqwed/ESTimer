<?php
/**
 * Description:Bean类(数据对象),用于定义表结构,过滤无效字段
 * UserModel是对数据表的抽象,UserBean是对一行数据的抽象
 * Created by Dong.cx
 * DateTime: 2019-07-22 20:41
 * @version V4.0.1
 */

namespace App\Model;


use EasySwoole\Spl\SplBean;

class UserBean extends SplBean
{
	/** @var int */
	protected $id;
	/** @var string */
	protected $username;
	/** @var string */
	protected $password;
	/** @var string */
	protected $salt;

	protected function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);
	}

    /**
     * checkPassword
     * @param string $hash
     *
     * @return bool
     * @author Dong.cx 2019-07-23 09:38
     * @version V4.0.1
     */
    public function checkPassword(string $hash): bool
    {
        return password_verify($this->password, $hash);
    }
	/**
	 * @return string
	 */
	public function getSalt(): string
	{
		return $this->salt;
	}

	/**
	 * @param string $salt
	 */
	public function setSalt(string $salt): void
	{
		$this->salt = $salt;
	}


}