import request from '@/utils/request'
import { create{{\App\Service\Gen\GenTool::getDir($tableName)}} } from '@/format/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}'
export function getTeacherList(params) {
  const result = request({
    url: '/api/backend/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}',
    method: 'get',
    params
  })

  return result.then(response => {
    const formatData = Object.assign({}, response)
    formatData.data = formatData.data.map({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}} => create{{\App\Service\Gen\GenTool::getDir($tableName)}}({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}))
    return Promise.resolve(formatData)
  })
}

export function get{{\App\Service\Gen\GenTool::getDir($tableName)}}({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}) {
  if (!{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id) {
    console.log('{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id is not define')
    return
  }
  return request({
    url: '/api/backend/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}/' + {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id,
    method: 'get'
  })
}

export function update{{\App\Service\Gen\GenTool::getDir($tableName)}}({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}) {
  if (!{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id) {
    console.log('{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id is not define')
    return
  }
  return request({
    url: '/api/backend/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}/' + {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id,
    method: 'put',
    data: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}
  })
}

export function store{{\App\Service\Gen\GenTool::getDir($tableName)}}({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}) {
  return request({
    url: '/api/backend/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}',
    method: 'post',
    data: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}
  })
}

export function delete{{\App\Service\Gen\GenTool::getDir($tableName)}}({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}) {
  if (!{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id) {
    console.log('{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id is not define')
    return
  }
  return request({
    url: '/api/backend/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}/' + {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id,
    method: 'delete'
  })
}

export function search{{\App\Service\Gen\GenTool::getDir($tableName)}}(params) {
  if (params && params === '') {
    return []
  }
  return request({
    url: '/api/backend/search/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}',
    method: 'get',
    params: params
  })
}
