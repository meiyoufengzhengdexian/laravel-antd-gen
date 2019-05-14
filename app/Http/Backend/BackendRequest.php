<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:27
 */

namespace App\Http\Backend;


use App\Http\Backend\Lib\Tool;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class BackendRequest  extends FormRequest
{
    protected $authErrorMessage = "";
    protected $admin;


    public function initialize(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->admin = Tool::getNowAdmin();
    }

    /**
     * 判断操作权限
     * @param $action
     * @param string $errorMessage
     * @return bool
     */
    public function checkAuth($action, $errorMessage = "您没有权限")
    {
        if(!$this->getAdmin()->can($action)){
            $this->authErrorMessage = $errorMessage;
            return false;
        }
        return true;
    }

    /**
     * 获取当前路由别名
     * @return bool|mixed
     * @throws BackendException
     */
    public function getRouteAs()
    {
        $route = request()->route();
        if (!$route) {
            throw new BackendException('request()->route() return false', -9);
        }
        $action = $route->getAction();

        //获取别名， 用户权限判断
        $as = array_get($action, 'as');

        if (!$as) {
            return false;
        }
        return $as;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * @throws BackendRequestAuthException
     */
    public function failedAuthorization()
    {
        throw new BackendRequestAuthException($this->authErrorMessage, -9);
    }

    /**
     * @return mixed
     */
    public function getAdmin() : Authenticatable
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin): void
    {
        $this->admin = $admin;
    }

}