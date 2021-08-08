<?php

namespace Bageur\Slider\Model;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'bgr_slider';
    protected $appends = ['img','data_caption'];

    public function getImgAttribute()
    {
          return \Storage::url('slider/'.$this->gambar);
    }
    public function getDataCaptionAttribute()
    {
          if($this->caption != null){
             return $this->caption;
          }else{
             return '-';
          }
    }
    public function scopeDatatable($query,$request,$page=12)
    {
          $search       = ["id",'caption'];
          $searchqry    = '';

        $searchqry = "(";
        foreach ($search as $key => $value) {
            if($key == 0){
                $searchqry .= "lower($value) like '%".strtolower($request->search)."%'";
            }else{
                $searchqry .= "OR lower($value) like '%".strtolower($request->search)."%'";
            }
        }
        $searchqry .= ")";
        if(@$request->sort_by){
            if(@$request->sort_by != null){
            	$explode = explode('.', $request->sort_by);
                 $query->orderBy($explode[0],$explode[1]);
            }else{
                  $query->orderBy('created_at','desc');
            }

             $query->whereRaw($searchqry);
        }else{
             $query->whereRaw($searchqry);
        }

        if($request->get == 'all'){
            return $query->get();
        }else{
                return $query->paginate($page);
        }

    }
}
