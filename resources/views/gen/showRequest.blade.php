
namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}}\Request;



use App\Http\Controllers\Backend\BackendRequest;

class {{\App\Service\Gen\GenTool::getDir($table)}}ShowRequest extends BackendRequest
{
    public function authorize()
    {
        if($this->checkAuth('backend.{{$table}}.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看权限: ". $action);
    }
}
