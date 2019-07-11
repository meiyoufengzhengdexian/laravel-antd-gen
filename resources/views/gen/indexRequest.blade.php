
namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}}\Request;

use App\Http\Controllers\Backend\BackendRequest;
use Illuminate\Http\Request;

class {{\App\Service\Gen\GenTool::getDir($table)}}IndexRequest extends BackendRequest
{
    /**
     * @return bool
     * @throws \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有查看列表权限: ". $action);
    }
}