<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-14
 * Time: 下午9:05
 */

namespace App\Http\Backend\Lib;


use App\AbilitiesResourceRule;
use App\Http\Abilities;
use App\Http\AbilitiesResource;
use App\Http\Backend\BackendRequest;

trait AuthResourceTrait
{
    public function checkResource($resourcePermission, BackendRequest $request)
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

        $admin = $request->getAdmin();

        foreach ($resources as $resource) {
            foreach ($abilities as $ability) {
                if (!$ability->entity_id != $resource->id) {
                    continue;
                }
                if (!$admin->can($ability->name)) {
                    continue;
                }
            }
        }
    }
}