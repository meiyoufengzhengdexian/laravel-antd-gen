

namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}};

use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Backend\Tag\TagModel;
use Illuminate\Http\Request;

class {{\App\Service\Gen\GenTool::getDir($table)}}Controller extends BackendController
{
    use {{\App\Service\Gen\GenTool::getDir($table)}}Curd;

    public function {{ ucfirst(\App\Service\Gen\GenTool::getDir($table)) }}Options(Request $request)
    {
        $keyWord = $request->input('searchKey', false);

        $cateQuery = {{\App\Service\Gen\GenTool::getDir($table)}}Model::query();
        if($keyWord !== false){
            $cateQuery->where('name', 'like', "{$keyWord}%");
        }

        $list = $cateQuery->select(['id', 'name'=>'name'])->limit(20)->get();
        return $this->success($list);
    }
}