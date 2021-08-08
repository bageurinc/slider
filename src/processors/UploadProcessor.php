<?php
namespace Bageur\Slider\Processors;

class UploadProcessor {

    public static function go($data,$loc) {
       $namaBerkas = date('YmdHis').'.'.$data->getClientOriginalExtension();
       $path = $loc;
       $path = $data->storeAs($path.'/', $namaBerkas);
       return basename($path);
    }
}
