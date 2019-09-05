<?php
namespace App\Http\Controllers\Backend\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;


class ProductModel extends BackendModel
{
    protected $table = 'product';
    use SoftDeletes;

                                
                                
}
