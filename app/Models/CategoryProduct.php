<?php

namespace App\Models;

use App\Models\ActivityCategoryOrganization;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;
    protected $guarded =[];

    function priceCategoryOrganization($organization){
        $price_category=ActivityCategoryOrganization::where('organization_id',$organization->id)->where('category_children_id',$this->id)->get()->first()->price;
        return $price_category;
    }
    

}