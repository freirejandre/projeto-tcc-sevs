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

//FALTA GERAR A MEDIDA PARA TIPO M LINEAR PORTÃO DOS ALUMINIOS NO BUDGETCONTROLLER

//Auth::routes();

Route::get('/', function () {
    return redirect('login');
});

Route::get('/home', 'DashboardController@index')->name('home')->middleware('auth');


//rotas para pegar dados para os gráficos
Route::prefix('dashboard')->group(function () {
    Route::get('/sales', 'Api\DashboardController@sales')->name('dashboard.sales');
    Route::get('/financial', 'Api\DashboardController@financial')->name('dashboard.financial');
    Route::get('/futurefinancial', 'Api\DashboardController@futureFinancial')->name('dashboard.futurefinancial');
    Route::get('/orders', 'Api\DashboardController@orders')->name('dashboard.orders');
    Route::get('/clients', 'Api\DashboardController@clients')->name('dashboard.clients');
    Route::get('/budgets', 'Api\DashboardController@budgets')->name('dashboard.budgets');
    Route::get('/products', 'Api\DashboardController@products')->name('dashboard.products');
});


//rotas do usuario
Route::prefix('users')->group(function () {
    Route::get('/', 'UserController@index')->name('users.index');
    Route::get('/create', 'UserController@create')->name('users.create');
    Route::post('/', 'UserController@store')->name('users.store');
    Route::get('/{user}', 'UserController@show')->name('users.show');
    Route::get('/{id}/edit', 'UserController@edit')->name('users.edit');
    Route::patch('/{id}', 'UserController@update')->name('users.update');
    Route::delete('/{id}', 'UserController@destroy')->name('users.destroy');


    Route::get('/role/{id}', 'UserController@roleshow')->name('users.role.show');
    Route::post('/role/{id}', 'UserController@rolestore')->name('users.role.store');
//    Route::get('/{id}/edit', 'UserController@edit')->name('users.edit');
//    Route::patch('/{id}', 'UserController@update')->name('users.update');
    Route::delete('/role/{id}/{role_id}', 'UserController@roledestroy')->name('users.role.destroy');
});

//rotas dos papeis
Route::prefix('roles')->group(function () {
    Route::get('/', 'RoleController@index')->name('roles.index');
    Route::get('/create', 'RoleController@create')->name('roles.create');
    Route::post('/', 'RoleController@store')->name('roles.store');
    Route::get('/{role}', 'RoleController@show')->name('roles.show');
    Route::get('/{id}/edit', 'RoleController@edit')->name('roles.edit');
    Route::patch('/{id}', 'RoleController@update')->name('roles.update');
    Route::delete('/{id}', 'RoleController@destroy')->name('roles.destroy');

    Route::get('/permission/{id}', 'RoleController@permissionshow')->name('roles.permission.show');
    Route::post('/permission/{id}', 'RoleController@permissionstore')->name('roles.permission.store');
    Route::delete('/permission/{id}/{permission_id}', 'RoleController@permissiondestroy')->name('roles.permission.destroy');

});

//rotas das permissoes
//Route::prefix('permissions')->group(function () {
//    Route::get('/', 'PermissionController@index')->name('permissions.index');
//    Route::get('/create', 'PermissionController@create')->name('permissions.create');
//    Route::post('/', 'PermissionController@store')->name('permissions.store');
//    Route::get('/{permission}', 'PermissionController@show')->name('permissions.show');
//    Route::get('/{id}/edit', 'PermissionController@edit')->name('permissions.edit');
//    Route::patch('/{id}', 'PermissionController@update')->name('permissions.update');
//    Route::delete('/{id}', 'PermissionController@destroy')->name('permissions.destroy');
//});

//rotas de cliente
Route::prefix('clients')->group(function () {
    Route::get('/', 'ClientController@index')->name('clients.index');
    Route::get('/create', 'ClientController@create')->name('clients.create');
    Route::post('/', 'ClientController@store')->name('clients.store');
    Route::get('/{id}', 'ClientController@show')->name('clients.show');
    Route::get('/{id}/edit', 'ClientController@edit')->name('clients.edit');
    Route::patch('/{id}', 'ClientController@update')->name('clients.update');
    Route::delete('/{id}', 'ClientController@destroy')->name('clients.destroy');
});

//rotas de mproduto
Route::prefix('products')->group(function () {
    Route::get('/', 'MProductController@index')->name('mproducts.index');
    Route::get('/create', 'MProductController@create')->name('mproducts.create');
    Route::post('/{tab}', 'MProductController@store')->name('mproducts.store');
    Route::get('/{user}', 'MProductController@show')->name('mproducts.show');
    Route::get('/{id}/edit', 'MProductController@edit')->name('mproducts.edit');
    Route::patch('/{id}/{tab}', 'MProductController@update')->name('mproducts.update');
    Route::delete('/{id}', 'MProductController@destroy')->name('mproducts.destroy');
});

//rotas de categoria
Route::prefix('categories')->group(function () {
    Route::get('/', 'CategoryController@index')->name('categories.index');
    Route::get('/create', 'CategoryController@create')->name('categories.create');
    Route::post('/', 'CategoryController@store')->name('categories.store');
    Route::get('/{user}', 'CategoryController@show')->name('categories.show');
    Route::get('/{id}/edit', 'CategoryController@edit')->name('categories.edit');
    Route::patch('/{id}', 'CategoryController@update')->name('categories.update');
    Route::delete('/{id}', 'CategoryController@destroy')->name('categories.destroy');
});

//rotas dos materiais
Route::prefix('materials')->group(function () {
    Route::get('/', 'MaterialController@index')->name('materials.index');
    Route::get('/{type}/create', 'MaterialController@create')->name('materials.create');
    Route::post('/{type}', 'MaterialController@store')->name('materials.store');
    Route::get('/{type}/{id}', 'MaterialController@show')->name('materials.show');
    Route::get('/{type}/{id}/edit', 'MaterialController@edit')->name('materials.edit');
    Route::patch('/{type}/{id}', 'MaterialController@update')->name('materials.update');
    Route::delete('/{type}/{id}', 'MaterialController@destroy')->name('materials.destroy');
});

//rotas dos orçamentos
Route::prefix('budgets')->group(function () {
    Route::get('/', 'BudgetController@index')->name('budgets.index');
    Route::get('/create', 'BudgetController@create')->name('budgets.create');
    Route::post('/', 'BudgetController@store')->name('budgets.store');
    Route::get('/{id}', 'BudgetController@show')->name('budgets.show');
    Route::get('/{id}/edit', 'BudgetController@edit')->name('budgets.edit');
    Route::patch('/{tab}/{id}', 'BudgetController@update')->name('budgets.update');
    Route::delete('/{del}/{id}', 'BudgetController@destroy')->name('budgets.destroy');

    Route::get('/{type}/{id}/edit', 'BudgetController@editMaterial')->name('budgets.materials.edit');
    Route::patch('/{type}/{id}/update', 'BudgetController@updateMaterial')->name('budgets.materials.update');
});

//rotas de vendas
Route::prefix('sales')->group(function () {
    Route::get('/', 'SaleController@index')->name('sales.index');
    Route::get('/create', 'SaleController@create')->name('sales.create');
    Route::post('/', 'SaleController@store')->name('sales.store');
    Route::get('/{id}/pay', 'SaleController@pay')->name('sales.pay');
    Route::patch('/{id}/payupdate', 'SaleController@payupdate')->name('sales.payupdate');
    Route::get('/{id}', 'SaleController@show')->name('sales.show');
    Route::get('/{id}/edit', 'SaleController@edit')->name('sales.edit');
    Route::patch('/{id}', 'SaleController@update')->name('sales.update');
    Route::delete('/{id}', 'SaleController@destroy')->name('sales.destroy');
});

//rotas de estoque
Route::prefix('storage')->group(function () {
    Route::get('/', 'StorageController@index')->name('storage.index');
    Route::get('/create', 'StorageController@create')->name('storage.create');
    Route::post('/', 'StorageController@store')->name('storage.store');
    Route::get('/{user}', 'StorageController@show')->name('storage.show');
    Route::get('/{id}/edit', 'StorageController@edit')->name('storage.edit');
    Route::patch('/{tab}', 'StorageController@update')->name('storage.update');
    Route::delete('/{id}', 'StorageController@destroy')->name('storage.destroy');
});

//rotas das ordens de serviço
Route::prefix('orders')->group(function () {
    Route::get('/', 'OrderController@index')->name('orders.index');
    Route::get('/create', 'OrderController@create')->name('orders.create');
    Route::post('/', 'OrderController@store')->name('orders.store');
    Route::get('/{id}', 'OrderController@show')->name('orders.show');
    Route::get('/{id}/edit', 'OrderController@edit')->name('orders.edit');
    Route::patch('/{id}/{situacao?}', 'OrderController@update')->name('orders.update');
    Route::delete('/{id}', 'OrderController@destroy')->name('orders.destroy');
});

//rotas das fornecedores
Route::prefix('providers')->group(function () {
    Route::get('/', 'ProviderController@index')->name('providers.index');
    Route::get('/create', 'ProviderController@create')->name('providers.create');
    Route::post('/', 'ProviderController@store')->name('providers.store');
    Route::get('/{id}', 'ProviderController@show')->name('providers.show');
    Route::get('/{id}/edit', 'ProviderController@edit')->name('providers.edit');
    Route::patch('/{id}', 'ProviderController@update')->name('providers.update');
    Route::delete('/{id}', 'ProviderController@destroy')->name('providers.destroy');
});

//rotas da empresa
Route::prefix('companies')->group(function () {
    // Route::get('/', 'CompanyController@index')->name('companies.index');
    // Route::get('/create', 'CompanyController@create')->name('companies.create');
    Route::post('/', 'CompanyController@store')->name('companies.store');
    // Route::get('/{user}', 'CompanyController@show')->name('companies.show');
    // Route::get('/{id}/edit', 'CompanyController@edit')->name('companies.edit');
    Route::patch('/{id}', 'CompanyController@update')->name('companies.update');
    Route::delete('/{id}', 'CompanyController@destroy')->name('companies.destroy');
});

//rotas da configuracao
Route::prefix('configuration')->group(function () {
    Route::get('/', 'ConfigurationController@index')->name('configuration.index');
    Route::patch('/', 'ConfigurationController@update')->name('configuration.update');
});

//rotas da financeiro
Route::prefix('financial')->group(function () {
    Route::get('/', 'FinancialController@index')->name('financial.index');
    Route::post('/', 'FinancialController@store')->name('financial.store');
    Route::patch('/{id}', 'FinancialController@update')->name('financial.update');
    Route::delete('/{id}', 'FinancialController@destroy')->name('financial.destroy');
});

//rotas da gerar pdf
Route::prefix('pdf')->group(function () {
    Route::get('/{tipo}', 'PdfController@index')->name('pdf.index');
    //Route::get('/create', 'PdfController@create')->name('pdf.create');
    //Route::post('/', 'PdfController@store')->name('pdf.store');
    Route::get('/show/{tipo}/{id}', 'PdfController@show')->name('pdf.show');
    Route::get('/show/{tipo}', 'PdfController@showRelatorio')->name('pdf.showRelatorio');
    //Route::get('/{id}/edit', 'PdfController@edit')->name('pdf.edit');
    //Route::patch('/{id}', 'PdfController@update')->name('pdf.update');
    //Route::delete('/{id}', 'PdfController@destroy')->name('pdf.destroy');
});

Route::prefix('restore')->group(function () {
    Route::get('/', 'RestoreController@index')->name('restore.index');
    Route::get('/{tipo}/{id}', 'RestoreController@restore')->name('restore.restore');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');



