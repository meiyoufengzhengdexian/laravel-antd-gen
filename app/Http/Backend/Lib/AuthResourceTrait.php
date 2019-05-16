<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午9:05
 */

namespace App\Http\Backend\Lib;


use App\Http\Abilities;
use App\Http\AbilitiesResource;
use App\Http\AbilitiesResourceRule;
use App\Http\Backend\BackendRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait AuthResourceTrait
{
    public function checkResource($resourcePermission, Authenticatable $admin, Model $model)
    {
        //验证资源权限
//        $resourcePermissionConfig = $this->getConfig('Cate.AuthConfig.ResourcePermission');
//        $resourcePermission = array_get($resourcePermissionConfig, 'resourcePermission', []);

        $resourceName = array_column($resourcePermission, 'resource');
        $resources = AbilitiesResource::query()->whereIn('resource', $resourceName)->get();
        $abilities = Abilities::query()
            ->where('entity_type', "App\Http\AbilitiesResource")
            ->whereIn('entity_id', $resources->pluck('id'))
            ->get();


        $returnStatus = true;
        foreach ($resources as $resource) {
            foreach ($abilities as $ability) {
                if (!$ability->entity_id != $resource->id) {
                    continue;
                }
                if (!$admin->can($ability->name)) {
                    continue;
                }

                //rule
                $rules = AbilitiesResourceRule::where('resource_id', $resource->id)->get();
                foreach ($rules as $rule) {
                    $param = json_decode($rule->condition_param, true);

                    if ($rule->ref_type) {
                        switch ($rule->ref_type) {
                            case "belongsTo":
                                //多对一
                                $returnStatus && $model->{$rule->field_name} == $param[0];
                                break;
                        }
                    }else{
                        switch ($rule->condition_type) {
                            case "=":
                                $returnStatus && $model->{$rule->field_name} == $param[0];
                                break;
                            case ">":
                                $returnStatus && $model->{$rule->field_name} > $param[0];
                                break;
                            case "<":
                                $returnStatus && $model->{$rule->field_name} < $param[0];
                                break;
                            case "<>":
                            case "!=":
                                $returnStatus && $model->{$rule->field_name} != $param[0];
                                break;
                        }
                    }
                }
            }
        }

        return false;
    }
}