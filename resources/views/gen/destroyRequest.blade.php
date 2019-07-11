
namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}}\Request;


use App\Http\Controllers\Backend\BackendException;
use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;


class {{\App\Service\Gen\GenTool::getDir($table)}}DestroyRequest extends BackendRequest
{
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;

    /**
     * @return bool
     * @throws BackendException
     * @throws \App\Http\Controllers\Backend\BackendException
     */
    public function authorize()
    {
        if ($this->checkAuth('backend.{{$table}}.all')) {
            return true;
        }

        $action = $this->getRouteAs();
        if ($this->checkAuth($action, "您没有权限 : " . $action)) {
            return true;
        }

        return false;
    }
}