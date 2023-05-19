<?php

function geDomain()
{
    return request()->root();
}

function saveImgages($file, $path)
{

    $nameFile = Date('d-m-Y-His').'_'.$file->getClientOriginalName();

    $file->storeAs($path, $nameFile, 'publicPath');

    $pathImg = geDomain().$path.'/'.$nameFile;

    return $pathImg;
}
