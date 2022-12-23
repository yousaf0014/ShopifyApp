<?php
function getColorHash($adminProductID,$color){
    $attribute = \DB::table('product_to_product_attribute_groups AS PTPAG')
            ->leftJoin('product_attribute_groups as PAG', 'PAG.id','=','PTPAG.product_attribute_group_id')->leftJoin( 'product_attribute_group_values as PAGV','PAGV.product_attribute_group_id','=','PAG.id')->leftJoin('color_group_values AS CGV','CGV.id','=','PAGV.color_group_value_id')->where('PTPAG.admin_product_id',$adminProductID)->where('PAG.type','color')->where('PAGV.value',$color)->where('CGV.name',$color)->select('CGV.hash','CGV.name')->first();
    if(!empty($attribute)){
        return $attribute->hash;
    }
    return '';
}
function getArtWork($userProductID,$color){
    $attribute = \DB::table('user_product_attributes AS UPA')
                ->where('UPA.user_product_id',$userProductID)->where('UPA.value',$color)->select('UPA.art')->first();
    if(!empty($attribute)){
        return $attribute->art;
    }
    return '';
}
function colorFun($id){
    $colorData = \App\ColorGroupValue::where('id',$id)->withTrashed()->first();
    return $colorData->hash;
}
function getControllerName(){
    $currentAction = \Route::currentRouteAction();
    list($controller, $method) = explode('@', $currentAction);    
    return $controller = preg_replace('/.*\\\/', '', $controller);
}
function flash($message,$level = 'info'){
    session()->flash('flash_message',$message);
    session()->flash('flash_message_level',$level);
}
function shortString($string,$length){    
    return fixbrokenHtml(substr(strip_tags(html_entity_decode($string),'<br><b>'),0,$length).'...');
}

function YMD2MDY($date, $join = '/') {
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];
}
function YMD2MDY1($date, $join = '/') {
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[1] . $join. $dateArr[2] . $join . $dateArr[0];
}
function MDY2YMD($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[0] . $join . $dateArr[1];   
}

function DMY2YMD($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    $dateN =  $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];  
    return empty($dateArr[3]) ? $dateN:$dateN.' '.$dateArr[3];
}

function YMD2DMY($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    $dateN =  $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];  
    return empty($dateArr[3]) ? $dateN:$dateN.' '.$dateArr[3];
}
function DMY2YMDNoTime($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];
}

function getPakistanTime($date){
    $date = new DateTime($date, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Asia/Karachi'));
    return $date->format('Y-m-d H:i:s');
}

function getSydnyTime($date){
    $date = new DateTime($date, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Australia/Sydney'));
    return $date->format('Y-m-d H:i:s');
}

function localAmount($amount,$currencyCode = null){
    $currency = empty($currencyCode) ? \Auth::user()->currency: $currencyCode;
    if(empty($currency)){
        return  array('amount'=>$amount,'currency'=>'AUD');
    }
    $userCurrencyData = \App\Currency::where('code',$currency)->where('date',date('Y-m-d'))->first();
    $audRate = $userCurrencyRate = 0;
    if(empty($userCurrencyData)){
        $currency = \Auth::user()->currency.',AUD';
        $currencyData = json_decode(getCurrency('live',$currency,'USD'));
        $currencyObj = new \App\Currency;
        $currencyObj->code = $currency;
        $ncurrency = \Auth::user()->currency;
        $currencyForObj = 'USD'.$ncurrency;
        $userCurrencyRate = $currencyObj->rate = $currencyData->quotes->$currencyForObj;
        $currencyObj->date = date('Y-m-d');
        $currencyObj->save();

        $audCurrencyData = \App\Currency::where('date',date('Y-m-d'))->where('code','AUD')->first();
        if(empty($audCurrencyData)){
            $currencyObj2 = new \App\Currency;
            $currencyObj2->code = 'AUD';
            $audRate = $currencyObj2->rate = $currencyData->quotes->USDAUD;
            $currencyObj2->date = date('Y-m-d');
            $currencyObj2->save();    
        }else{
            $audRate = $audCurrencyData->rate = $currencyData->quotes->USDAUD;
            $audCurrencyData->save();    
        }
    }else{
        $userCurrencyRate = $userCurrencyData->rate;
        $audCurrencyData = \App\Currency::where('date',date('Y-m-d'))->where('code','AUD')->first();
        $audRate = $audCurrencyData->rate;
    }

    $newAmount = $amount/$audRate;
    $finalAmount = $newAmount*$userCurrencyRate;
    return array('amount'=>round($finalAmount,2),'currency'=>\Auth::user()->currency);   
    
}

function getCurrency($url,$currencies,$source = 'AUD',$type='get'){
    //d6d642772536328e0cab1535898f6624
    $data1['access_key'] = 'd6d642772536328e0cab1535898f6624'; 
    $data1['currencies'] = $currencies;
    $data1['source'] = $source;
    $data1['format'] = 1;
    $data = http_build_query($data1);

    $ch = curl_init(); // initialize curl handle 
    if($type == 'POST'){
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
    }else{
        $seprator = $getString = '';
        if(!empty($data1)){
            foreach($data1 as $key=>$val){
                $getString .= $seprator.$key.'='.$val;
                $seprator = '&';
            }
            $url .= '?'.$getString;
        }
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
    curl_setopt($ch, CURLOPT_REFERER, 'http://writemeai.com');
    curl_setopt($ch, CURLOPT_URL, 'http://api.currencylayer.com/api/'.$url); 
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt"); 
    curl_setopt($ch,CURLOPT_NOPROGRESS,false);
    $content = curl_exec($ch); // run the whole process
    $errors = curl_getinfo($ch);
    // close cURL resource, and free up system resources
    curl_close($ch);
    return $content;
}
