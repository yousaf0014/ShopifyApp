<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
}); */
//Route::get('/', 'HomeController@index');
Route::post('logout1', function(){
	//$type = \Auth::user()->type;
	\Auth::logout();
	  return redirect('/login');
});
Route::get('logout2', function(){
	//$type = \Auth::user()->type;
	\Auth::logout();
	  return redirect('/login');
});
Route::get('fulfillment', 'FulfillmentController@index');
Route::post('fulfillment','FulfillmentController@index');
Route::middleware(['auth','web'])->group(function () {
	Route::post('getNotifications', 'NotificationsController@getNotifications');
	Route::post('getNotificationsCount', 'NotificationsController@getCount');
	Route::get('notifications', 'NotificationsController@index');
	Route::get('notifications/delete/{notification}', 'NotificationsController@delete');

});

Route::group(['middleware' =>['auth.shopify']],function(){
	Route::get('/', 'HomeController@index')->name('home');
	Route::get('shopify', 'HomeController@index');
	Route::get('checkwebhooks','HomeController@webhooks');
	Route::get('shopProducts','Shop\ProductsController@index');
	Route::get('shopProducts/create/{category?}','Shop\ProductsController@create');
	Route::get('shopProducts/createProduct/{adminProduct}','Shop\ProductsController@createProduct');
	Route::post('shopProductsStep2/{adminProduct}','Shop\ProductsController@createProductStep2');
	Route::get('shopProductsStep2/{adminProduct}','Shop\ProductsController@createProductStep2Get');
	Route::post('shopProductsStep3/{adminProduct}','Shop\ProductsController@createProductStep2store');
	Route::get('syncProductToShopify','Shop\ProductsController@addProdcutToShopify');
	Route::get('/userOrders','Shop\OrdersController@index');
	Route::get('/userOrders/file','Shop\OrdersController@fileview');
	Route::get('/userOrders/{order}','Shop\OrdersController@show');
	Route::post('/userOrders/file','Shop\OrdersController@fileupload');
	Route::post('/userOrders/approve/{order}','Shop\OrdersController@approve');
	Route::post('/userOrders/cancel/{order}','Shop\OrdersController@cancel');
	Route::post('/userOrders/approveAll','Shop\OrdersController@approveAll');
	Route::post('/userOrders/cancelAll','Shop\OrdersController@cancelAll');


	Route::get('paymentInfoShop','Shop\PaymentsController@index');
	Route::get('addCardShop','Shop\PaymentsController@addCard');
	Route::get('savePaymentMethodShop/{method}','Shop\PaymentsController@savePaymentMethod');

});

Route::middleware(['auth','web','admin'])->group(function () {
	Route::get('adminwelcome', 'HomeController@adminwelcome');
	Route::get('/categories','Admin\CategoriesController@index');
	Route::get('/categories/create','Admin\CategoriesController@create');
	Route::get('/categories/file','Admin\CategoriesController@fileview');
	Route::get('/categories/{category}','Admin\CategoriesController@show');
	Route::get('/categories/{category}/edit','Admin\CategoriesController@edit');
	Route::post('/categories','Admin\CategoriesController@store');
	Route::post('/categories/file','Admin\CategoriesController@fileupload');
	Route::put('/categories/update/{category}','Admin\CategoriesController@update');
	Route::delete('/categories/{category}','Admin\CategoriesController@delete');



	Route::get('/settings','Admin\SettingsController@index');
	Route::get('/settings/create','Admin\SettingsController@create');
	Route::get('/settings/{setting}','Admin\SettingsController@show');
	Route::get('/settings/{setting}/edit','Admin\SettingsController@edit');
	Route::post('/settings','Admin\SettingsController@store');
	Route::put('/settings/update/{setting}','Admin\SettingsController@update');

	Route::get('/shipment','Admin\SettingsController@listShipmentCountries');
	Route::get('/shipment/{countryCode}/edit','Admin\SettingsController@editShipment');
	Route::put('/shipment/update/{countryCode}','Admin\SettingsController@updateShipment');

	Route::get('/adminProducts/create/{category}','Admin\ProductsController@create');
	Route::get('/adminProducts/file/{category}','Admin\ProductsController@fileview');
	Route::get('/adminProducts/{adminProduct}/edit','Admin\ProductsController@edit');
	Route::post('/adminProducts/file/{category}','Admin\ProductsController@fileupload');
	Route::post('/adminProducts/{category}','Admin\ProductsController@store');
	Route::get('/adminProducts/{category}','Admin\ProductsController@index');
	Route::get('/adminProducts/show/{adminProduct}','Admin\ProductsController@show');
	Route::put('/adminProducts/update/{adminProduct}','Admin\ProductsController@update');
	Route::delete('/adminProducts/delete/{adminProduct}','Admin\ProductsController@delete');
	//Route::Post('/adminProducts/{question}','Admin\ProductsController@delete');

	Route::get('/productAttributeGroups','Admin\ProductAttributesController@index');
	Route::get('/productAttributeGroups/create','Admin\ProductAttributesController@create');
	Route::get('/productAttributeGroups/{productAttributeGroup}','Admin\ProductAttributesController@show');
	Route::get('/productAttributeGroups/{productAttributeGroup}/edit','Admin\ProductAttributesController@edit');
	Route::post('/productAttributeGroups','Admin\ProductAttributesController@store');
	Route::put('/productAttributeGroups/update/{productAttributeGroup}','Admin\ProductAttributesController@update');
	Route::delete('/productAttributeGroups/{productAttributeGroup}','Admin\ProductAttributesController@delete');


	Route::get('/printingGroups','Admin\PrintingAttributesController@index');
	Route::get('/printingGroups/create','Admin\PrintingAttributesController@create');
	Route::get('/printingGroups/{printingGroup}','Admin\PrintingAttributesController@show');
	Route::get('/printingGroups/{printingGroup}/edit','Admin\PrintingAttributesController@edit');
	Route::post('/printingGroups','Admin\PrintingAttributesController@store');
	Route::put('/printingGroups/update/{printingGroup}','Admin\PrintingAttributesController@update');
	Route::delete('/printingGroups/{printingGroup}','Admin\PrintingAttributesController@delete');
	
	Route::get('/orders','Admin\OrdersController@index');
	Route::get('/orders/file','Admin\OrdersController@fileview');
	Route::get('/orders/{order}','Admin\OrdersController@show');
	Route::post('/orders/file','Admin\OrdersController@fileupload');

	//Route::get('createProduct/{adminProduct}','Shop\ProductsController@createProduct');
	
	Route::get('/colorGroups/file','Admin\ColorsController@fileview');
	Route::post('/colorGroups/file','Admin\ColorsController@fileupload');
	Route::get('/colorGroups','Admin\ColorsController@index');
	Route::get('/colorGroups/create','Admin\ColorsController@create');
	Route::get('/colorGroups/{colorGroup}','Admin\ColorsController@show');
	Route::get('/colorGroups/{colorGroup}/edit','Admin\ColorsController@edit');
	Route::post('/colorGroups','Admin\ColorsController@store');
	Route::put('/colorGroups/update/{colorGroup}','Admin\ColorsController@update');
	Route::delete('/colorGroups/{colorGroup}','Admin\ColorsController@delete');

	Route::get('payments','Admin\PaymentsController@index');
	
	/************     Testing For shop. Need to remove after  ********************/

	
});

Route::middleware(['auth','web','customer'])->group(function () {
	Route::get('customerwelcome', 'HomeController@customerwelcome');
	Route::get('stores','Customer\StoresController@index');
	Route::get('stores/create','Customer\StoresController@create');
	Route::get('stores/{user}','Customer\StoresController@show');
	Route::get('stores/{user}/edit','Customer\StoresController@edit');
	Route::post('stores','Customer\StoresController@store');
	Route::put('stores/update/{user}','Customer\StoresController@update');
	Route::delete('stores/{user}','Customer\StoresController@delete');
	Route::get('addShopifyStore', 'Customer\StoresController@addShopifyStore');
	Route::get('addShopifyStore/storeData','Customer\StoresController@storeData');
	//Route::get('shoplogin/{user}','Customer\StoresController@login');

	Route::get('storeProducts','Customer\ProductsController@index');
	Route::get('storeProducts/create/{category?}','Customer\ProductsController@create');
	Route::get('storeProducts/createProduct/{adminProduct}','Customer\ProductsController@createProduct');
	Route::post('storeProductsStep2/{adminProduct}','Customer\ProductsController@createProductStep2');
	Route::get('storeProductsStep2/{adminProduct}','Customer\ProductsController@createProductStep2Get');
	Route::post('storeProductsStep3/{adminProduct}','Customer\ProductsController@createProductStep2store');
	Route::get('syncStoreProductToShopify','Customer\ProductsController@addProdcutToShopify');

	Route::get('storeOrders','Customer\OrdersController@index');
	Route::get('storeOrders/file','Customer\OrdersController@fileview');
	Route::get('storeOrders/{order}','Customer\OrdersController@show');
	Route::post('storeOrders/file','Customer\OrdersController@fileupload');	
	Route::post('storeOrders/approve/{order}','Customer\OrdersController@approve');	
	Route::post('storeOrders/cancel/{order}','Customer\OrdersController@cancel');	

	Route::post('storeOrders/approveAll','Customer\OrdersController@approveAll');	
	Route::post('storeOrders/cancelAll','Customer\OrdersController@cancelAll');	

	Route::get('paymentInfo','Customer\PaymentsController@index');
	Route::get('addCard','Customer\PaymentsController@addCard');
	Route::get('savePaymentMethod/{method}','Customer\PaymentsController@savePaymentMethod');
	Route::get('doPayment','Customer\PaymentsController@doPayment');

	Route::get('OrderSync','OrderSyncController@index');
	Route::get('OrderSyncOrder','OrderSyncController@createOrder');
});


Route::middleware(['auth','web','orderdesk'])->group(function () {
	Route::get('orderdeskwelcome', 'HomeController@orderdeskwelcome');
	Route::get('productCategories', 'OrderDesk\ProductsController@categories');
	Route::get('categorydetails/{category}', 'OrderDesk\ProductsController@categorydetails');
	Route::get('products/show/{adminProduct}/{category}','OrderDesk\ProductsController@show');
	Route::get('products/{category}','OrderDesk\ProductsController@index');

	Route::get('payment','OrderDesk\PaymentsController@index');
	Route::get('addCardCustomer','OrderDesk\PaymentsController@addCard');
	Route::get('savePaymentMethodCustomer/{method}','OrderDesk\PaymentsController@savePaymentMethod');


	Route::get('ouserOrders','OrderDesk\OrdersController@index');
	Route::get('ouserOrders/file','OrderDesk\OrdersController@fileview');
	Route::get('ouserOrders/{order}','OrderDesk\OrdersController@show');
	Route::post('ouserOrders/file','OrderDesk\OrdersController@fileupload');	
	Route::post('ouserOrders/approve/{order}','OrderDesk\OrdersController@approve');	
	Route::post('ouserOrders/cancel/{order}','OrderDesk\OrdersController@cancel');
	Route::post('/ouserOrders/approveAll','OrderDesk\OrdersController@approveAll');
	Route::post('/ouserOrders/cancelAll','OrderDesk\OrdersController@cancelAll');


});
Auth::routes();