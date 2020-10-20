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

/* Uncomment to show the "welcome" view on "/" route
*Route::get('/', function () {
*    return view('welcome');
*});
*/

Route::group(['namespace' => 'Panel'], function () {

    Route::get('/cadastrar', 'AthleteController@newAthlete')->name('create');
    Route::post('/cadastrar', 'AthleteController@store')->name('store.athlete');

});

Auth::routes();

//Change the URL of the route and uncomment the route above to show the "welcome" view on "/" route.
Route::get('/painel', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');


Route::group(['prefix' => 'painel', 'middleware' => 'auth', 'namespace' => 'Panel'], function () {
    //athlete routes
    Route::get('/atletas', 'AthleteController@index')->name('athletes');
    Route::post('/atletas', 'AthleteController@search')->name('athlete.search');
    Route::get('/atleta/cadastrar', 'AthleteController@newAthleteConfirmed')->name('create.confirmed');
    Route::post('/atleta/cadastrar', 'AthleteController@storeConfirmed')->name('store.athlete.confirmed');
    Route::post('/buscacep', 'BuscaCEP@buscaCEP')->name('buscacep');
    Route::get('/atleta/{id}/editar', 'AthleteController@edit')->name('edit');
    Route::post('/atleta/{id}/editar', 'AthleteController@update')->name('update');
    Route::get('/atleta/{id}/deletar', 'AthleteController@delete')->name('delete');
    Route::get('/atletas/pendentes', 'AthleteController@pending')->name('pending.atheltes');
    Route::get('/atleta/{id}/pdf', 'AthleteController@generatePdf')->name('pdf');
    Route::get('/atleta/{id}/enviar-ficha', 'AthleteController@enviarFicha')->name('athlete.mail.pdf');
    Route::get('/atletas/mensalidades', 'MensalidadeController@index')->name('mensalidades');
    Route::get('/atleta/{id}/mensalidades', 'MensalidadeController@athleteIndex')->name('athlete.mensalidades');
    Route::get('/atleta/{id}/mensalidade/adicionar', 'MensalidadeController@createMensalidade')->name('create.mensalidade');
    Route::post('/atleta/{id}/mensalidade/adicionar', 'MensalidadeController@storeMensalidade')->name('store.mensalidade');
    Route::post('/atleta/{id}/mensalidade/adicionar-bancaria', 'MensalidadeController@storeMensalidadeBank')->name('store.mensalidade.bank');
    Route::post('/atleta/{id}/mensalidade/adicionar-cartao', 'MensalidadeController@storeMensalidadeCartao')->name('store.mensalidade.cartao');
    Route::any('/atletas/mensalidades/filtro', 'MensalidadeController@search')->name('search.mensalidade');
    Route::any('/atletas/{id}/mensalidades/filtro', 'MensalidadeController@searchAthlete')->name('search.mensalidade.athlete');
    Route::get('/atletas/mensalidade/{id}/download', 'MensalidadeController@downloadPdf')->name('download.mensalidade');
    Route::post('/atletas/mensalidade/{id}/enviar', 'MensalidadeController@sendPdf')->name('mail.mensalidade');
    Route::get('/atletas/inadimplentes', 'MensalidadeController@inadimplentes')->name('atletas.inadimplentes.index');

    //Users routes
    Route::get('/usuarios', 'UserController@index')->name('users');
    Route::get('/usuario/cadastrar', 'UserController@newUser')->name('user.create');
    Route::post('/usuario/cadastrar', 'UserController@store')->name('store.user');
    Route::get('/usuario/{id}/editar', 'UserController@edit')->name('edit.user');
    Route::post('/usuario/{id}/editar', 'UserController@update')->name('update.user');
    Route::get('/usuario/{id}/apagar', 'UserController@delete')->name('delete.user');
    Route::get('/usuario/{id}/permissoes', 'UserController@permissions')->name('user.permissions');
    Route::post('/usuario/{id}/permissoes', 'UserController@permissionConfirm')->name('user.permissions.confirm');


    //cashier routes
    Route::get('/caixas', 'CashierController@index')->name('cashier.index');
    Route::post('/caixa/cadastrar', 'CashierController@store')->name('cashier.store');
    Route::get('/caixa/{id}/editar', 'CashierController@edit')->name('cashier.edit');
    Route::post('caixa/{id}/editar', 'CashierController@update')->name('cashier.update');
    Route::get('/caixa/{id}/delete', 'CashierController@delete')->name('cashier.delete');
    Route::get('/caixa', 'CashierController@indexUserCashier')->name('caixa');
    Route::get('/caixa/{id}/historico', 'CashierController@cashierHistory')->name('cashier.history');
    Route::get('/caixa/{id}/historico/dinheiro', 'CashierController@cashierCashHistory')->name('cashier.cash.history');
    Route::get('/caixa/{id}/historico/bancario', 'CashierController@cashierBankHistory')->name('cashier.bank.history');
    Route::get('/caixa/{id}/transferencia/conta-para-caixa', 'CashierController@accountCashierTransfer')->name('account.cashier.transfer');
    Route::post('/caixa/{id}/transferencia/conta-para-caixa', 'CashierController@accountCashierTransferStore')->name('account.cashier.transfer.store');
    Route::get('/caixa/{id}/trasnferencia/caixa-para-conta', 'CashierController@cashierAccountTransfer')->name('cashier.account.transfer');
    Route::post('/caixa/{id}/trasnferencia/caixa-para-conta', 'CashierController@cashierAccountTransferStore')->name('cashier.account.transfer.store');
    Route::get('/caixa/{id}/transferencia/entre-caixas', 'CashierController@cashierTransfer')->name('cashier.transfer');
    Route::post('/caixa/{id}/transferencia/entre-caixas', 'CashierController@cashierTransferStore')->name('cashier.transfer.store');
    Route::post('/caixa/{id}/fechar', 'CashierController@close')->name('cashier.close');
    Route::post('/caixa/{id}/abrir', 'CashierController@open')->name('cashier.open');

    //invoice payment routes
    Route::get('/contas-a-pagar', 'InvoiceController@indexPay')->name('invoice.pay.index');
    Route::any('/contas-a-pagar/filtro', 'InvoiceController@searchPay')->name('search.pay');
    Route::get('/contas-a-pagar/cadastrar', 'InvoiceController@createInvoicePay')->name('create.invoice.pay');
    Route::post('/contas-a-pagar/cadastrar', 'InvoiceController@storeInvoicePay')->name('store.invoice.pay');
    Route::get('/contas-a-pagar/{id}/pagar', 'InvoiceController@paymentInvoicePay')->name('payment.invoice.pay');
    Route::post('/contas-a-pagar/pagar', 'InvoiceController@paymentStoreInvoicePay')->name('payment.store.invoice.pay');
    Route::post('/contas-a-pagar/{id}/pagar', 'InvoiceController@storeInvoicePayBank')->name('store.invoice.pay.bank');
    Route::get('/contas-a-pagar/vencidas', 'InvoiceController@aPagarVencidas')->name('invoice.apagar.vencidas');

    //invoice payment routes
    Route::get('/contas-a-receber', 'InvoiceController@indexReceive')->name('invoice.receive.index');
    Route::any('/contas-a-receber/filtro', 'InvoiceController@searchReceive')->name('search.receive');
    Route::get('/contas-a-receber/cadastrar', 'InvoiceController@createInvoiceReceive')->name('create.invoice.receive');
    Route::post('/contas-a-receber/cadastrar', 'InvoiceController@storeInvoiceReceive')->name('store.invoice.receive');
    Route::get('/contas-a-receber/{id}/baixar', 'InvoiceController@receivementInvoiceReceive')->name('receivement.invoice.receive');
    Route::post('/contas-a-receber/baixar', 'InvoiceController@receivementStoreInvoiceReceive')->name('receivement.store.invoice.receive');
    Route::post('/contas-a-receber/{id}/baixar', 'InvoiceController@storeInvoiceReceiveBank')->name('store.invoice.receive.bank');
    Route::get('/contas-a-receber/vencidas', 'InvoiceController@aReceberVencidas')->name('invoice.areceber.vencidas');
    Route::post('/contas-a-receber/forma-pagamento', 'InvoiceController@consultaFormaPagamento')->name('invoice.getformapagamento');


    //payment methods
    Route::get('/formas-de-pagamento', 'PaymentMethodController@index')->name('payment.method.index');
    Route::post('/formas-de-pagamento/cadastrar', 'PaymentMethodController@store')->name('payment.method.store');
    Route::get('/formas-de-pagamento/{id}/editar', 'PaymentMethodController@edit')->name('payment.method.edit');
    Route::post('/formas-de-pagamento/{id}/editar', 'PaymentMethodController@update')->name('payment.method.update');
    Route::get('/formas-de-pagamento/{id}/deletar', 'PaymentMethodController@delete')->name('payment.method.delete');

    //bank account routes
    Route::get('/contas-bancarias', 'BankAccountController@index')->name('bank.account.index');
    Route::get('/contas-bancarias/cadastrar', 'BankAccountController@create')->name('bank.account.create');
    Route::post('/contas-bancarias/cadastrar', 'BankAccountController@store')->name('bank.account.store');
    Route::get('/contas-bancarias/{id}/editar', 'BankAccountController@edit')->name('bank.account.edit');
    Route::post('/contas-bancarias/{id}/editar', 'BankAccountController@update')->name('bank.account.update');
    Route::get('/contas-bancarias/{id}/apagar', 'BankAccountController@delete')->name('bank.account.delete');

    //categories routes
    Route::get('/categorias', 'CategoryController@index')->name('category.index');
    Route::post('/categoria/adicionar', 'CategoryController@store')->name('category.store');
    Route::get('/categoria/{id}/editar', 'CategoryController@edit')->name('category.edit');
    Route::post('/categoria/{id}/editar', 'CategoryController@update')->name('category.update');
    Route::get('/categoria/{id}/apagar', 'CategoryController@delete')->name('category.delete');

    //relatorios
    Route::get('/relatorios/financeiro/a-receber', 'RelatorioController@aReceber')->name('relatorios.areceber');
    Route::post('relatorios/financeiro/a-receber', 'RelatorioController@aReceberList')->name('relatorios.areceber.list');
    Route::get('/relatorios/financeiro/a-pagar', 'RelatorioController@aPagar')->name('relatorios.apagar');
    Route::post('relatorios/financeiro/a-pagar', 'RelatorioController@aPagarList')->name('relatorios.apagar.list');
    Route::get('/relatorios/financeiro/contas-pagas', 'RelatorioController@contasPagas')->name('relatorios.pagas');
    Route::post('/relatorios/financeiro/contas-pagas', 'RelatorioController@contasPagasList')->name('relatorios.pagas.list');
    Route::get('/relatorios/financeiro/contas-recebidas', 'RelatorioController@contasRecebidas')->name('relatorios.recebidas');
    Route::post('/relatorios/financeiro/contas-recebidas', 'RelatorioController@contasRecebidasList')->name('relatorios.recebidas.list');
    Route::get('/relatorios/financeiro/contas-pagas-recebidas', 'RelatorioController@pagasRecebidas')->name('relatorios.pagas.recebidas');
    Route::post('/relatorios/financeiro/contas-pagas-recebidas', 'RelatorioController@pagasRecebidasList')->name('relatorios.pagas.recebidas.list');

    //company routes
    Route::get('/empresas', 'CompanyController@index')->name('company.index');
    Route::get('/empresa/cadastrar', 'CompanyController@create')->name('company.create');
    Route::post('/empresa/cadastrar', 'CompanyController@store')->name('company.store');
    Route::get('/empresa/{id}/editar', 'CompanyController@edit')->name('company.edit');
    Route::post('/empresa/{id}/editar', 'CompanyController@update')->name('company.update');
    Route::get('/empresa/{id}/apagar', 'CompanyController@delete')->name('company.delete');
    Route::get('/empresa/{id}/categorias', 'CompanyController@categorias')->name('company.categorias');
    Route::get('/empresa/{idEmpresa}/categoria/{idCategoria}/adicionar', 'CompanyController@addCategoria')->name('company.add.category');
    Route::get('/empresa/{idEmpresa}/categoria/{idCategoria}/remover', 'CompanyController@removeCategoria')->name('company.remove.category');
    Route::post('/empresa/atleta', 'CompanyController@athleteCreate')->name('company.athlete.create');

    //configuration routes
    Route::get('/config/email', 'MailConfigurationController@index')->name('config.mail');
    Route::post('/config/email', 'MailConfigurationController@update')->name('config.mail.update');

    Route::get('/backup', 'BackupController@index')->name('backup.index');
    Route::post('/backup', 'BackupController@backupMysql')->name('backup');
    Route::get('/backup/restaurar', 'BackupController@indexRestore')->name('backup.restore.index');
    Route::post('/backup/resturar', 'BackupController@restoreMysql')->name('backup.restore');

    //custom mail routes
    Route::get('/mail', 'CustomMailController@index')->name('custom.mail.index');
    Route::post('/mail', 'CustomMailController@sendMail')->name('custom.mail.send');

    Route::get('/clientes-fornecedores', 'SupplierController@index')->name('customer.index');
    Route::get('/cliente-fornecedor/cadastrar', 'SupplierController@create')->name('customer.create');
    Route::post('/cliente-fornecedor/cadastrar', 'SupplierController@store')->name('customer.store');
    Route::get('/cliente-fornecedor/{id}/editar', 'SupplierController@edit')->name('customer.edit');
    Route::post('/cliente-fornecedor/{id}/editar', 'SupplierController@update')->name('customer.update');
    Route::get('/cliente-fornecedor/{id}/delete', 'SupplierController@delete')->name('customer.delete');

    Route::post('/clientes-fornecedores', 'SupplierController@search')->name('customer.search');

    Route::get('/operadoras-cartao', 'CardController@index')->name('card.index');
    Route::get('/operadoras-cartao/cadastrar', 'CardController@create')->name('card.create');
    Route::post('/operadoras-cartao/cadastrar', 'CardController@store')->name('card.store');
    Route::get('/operadora-cartao/{id}/editar', 'CardController@edit')->name('card.edit');
    Route::post('/operadora-cartao/{id}/editar', 'CardController@update')->name('card.update');
    Route::get('/operadora-cartao/{id}/apagar', 'CardController@delete')->name('card.delete');
    Route::get('/operadora-cartao/{id}/bandeiras', 'CardController@bandeiras')->name('card.bandeiras');
    Route::post('/operadora-cartao/{id}/bandeira/cadastrar', 'BandeiraController@store')->name('bandeira.store');
    Route::get('/operadora-cartao/bandeira/{id}/editar', 'BandeiraController@edit')->name('bandeira.edit');
    Route::post('/operadora-cartao/bandeira/{id}/editar', 'BandeiraController@update')->name('bandeira.update');
    Route::get('/operadora-cartao/bandeira/{id}/apagar', 'BandeiraController@delete')->name('bandeira.delete');

    Route::post('/conta-a-receber/baixar/cartao', 'CardController@createCartaoMovimentoEntrada')->name('create.cartao.movimento');
    Route::post('/conta-a-receber/baixar/cartao/store', 'CardController@storeCartaoMovimentoEntrada')->name('store.cartao.movimento');
    Route::post('/conta-a-pagar/baixar/cartao', 'CardController@createCartaoMovimentoSaida')->name('create.cartao.movimento.saida');
    Route::post('/conta-a-pagar/baixar/cartao/store', 'CardController@storeCartaoMovimentoSaida')->name('store.cartao.movimento.saida');

    Route::get('/cartoes', 'CartaoMovimentoController@index')->name('cartao.movimentos.index');
    Route::any('/cartoes/filtro', 'CartaoMovimentoController@search')->name('cartao.movimentos.search');
    Route::get('/cartao-movimento/{id}/baixar', 'CartaoMovimentoController@baixar')->name('cartao.movimento.baixar');
    Route::post('/cartao-movimento/{id}/baixar', 'CartaoMovimentoController@baixarStore')->name('cartao.movimento.baixar.store');
    Route::get('/cartao-movimento/{id}/estornar', 'CartaoMovimentoController@estornar')->name('cartao.movimento.estornar');
    Route::post('/cartao-movimento/{id}/estornar', 'CartaoMovimentoController@estornarStore')->name('cartao.movimento.estornar.store');


    Route::get('/configuracoes', 'SettingController@index')->name('settings.index');
    Route::post('/configuracoes', 'SettingController@update')->name('settings.update');

});
