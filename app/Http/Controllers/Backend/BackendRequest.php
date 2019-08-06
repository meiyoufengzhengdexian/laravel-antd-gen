<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-6
 * Time: 上午9:27
 */

namespace App\Http\Controllers\Backend;



use App\Http\Controllers\Backend\Lib\ToolTrait;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class BackendRequest  extends FormRequest
{
    use ToolTrait;
    protected $authErrorMessage = "";
    protected $admin;
    private $messageList = [];


    public function initialize(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->admin = $this->getNowAdmin();
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
        $as = Arr::get($action, 'as');

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

    public function makeRuleAndMessage($ruleConfig)
    {
        $returnRule = [];
        foreach ($ruleConfig as $column) {
            $rules = Arr::get($column, 'rules', false);
            if (!$rules) {
                continue;
            }
            foreach ($rules as $rule) {
                $ruleStr = Arr::get($rule, 'rule');
                if (!$ruleStr) {
                    continue;
                }

                $strPos = strpos($ruleStr, ':');
                if (!$strPos) {
                    $ruleMethod = $ruleStr;
                } else {
                    $ruleMethod = substr($ruleStr, 0, $strPos);
                }

                if (isset($returnRule[Arr::get($column, 'name')])) {
                    $returnRule[Arr::get($column, 'name')] .= "|" . $ruleStr;
                } else {
                    $returnRule[Arr::get($column, 'name')] = $ruleStr;
                }

                $message = Arr::get($rule, 'message');

                if ($message) {
                    $this->messageList[Arr::get($column, 'name') . ".$ruleMethod"] = $message;
                }
            }
        }
        return $returnRule;
    }
    /**
     * @throws BackendRequestAuthException
     */
    public function failedAuthorization()
    {
        throw new BackendRequestAuthException($this->authErrorMessage, -9);
    }

    /**
     * @param Validator $validator
     * @throws BackendException
     */
    public function failedValidation(Validator $validator)
    {
        $backendException = new BackendException($validator->errors()->first());
        $backendException->setData($validator->errors());
        throw $backendException;
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


    public function messages()
    {
        return $this->messageList;
    }

}
