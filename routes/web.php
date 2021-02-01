<?php

use Illuminate\Support\Facades\Route;

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
//Rotas de Login
//Rota que faz a raíz ser a view de oauth
Route::get('/', 'login\loginController@index')->name('index');
//View de oauth manda para está rota; apenas usada devido ao login. Valida permissões e grupos da pessoa
Route::get('/application', 'login\loginController@verificaLogin')->name('area')->middleware('user.has.authcookie');
//Rota que faz logout
Route::get('/logout', 'login\loginController@logout')->name('logout');
//Rota que ajuda a montar a tabela na view dash do usuário
Route::get('/dash', 'login\loginController@dashBoard')->name('dash')->middleware('user.has.authcookie');

//Rotas de Gestor
//index da área de gestão dos Gestores
Route::get('/gestores', 'gestor\GestorController@index')->name('gestor.index')->middleware('user.has.authcookie');
//Criação de novo Gestor
Route::get('/gestores/create', 'gestor\GestorController@create')->name('gestor.create')->middleware('user.has.authcookie');
Route::post('/gestores', 'gestor\GestorController@store')->name('gestor.store');
//Rota ajax autocomplet na criação do novo gestor
Route::post('/gestores/liveSearch', 'gestor\GestorController@liveSearch')->name('gestor.liveSearch');
//Rota de edição de um gestor
Route::get('/gestores/{gestor}/edit', 'gestor\GestorController@edit')->name('gestor.edit')->middleware('user.has.authcookie');
Route::put('/gestores/{gestor}', 'gestor\GestorController@update')->name('gestor.update')->middleware('user.has.authcookie');
//Rota que remove permissão de Gestor - por ser uma atualização do isGestor verbo é PUT
Route::put ('/gestores/revogar/{gestor}', 'gestor\GestorController@removePermission')->name('gestor.removePermission')->middleware('user.has.authcookie');

//Rotas de grupo - métodos personalizados
//Exclui permissão de pessoa ao grupo
Route::put ('admin/grupo/revogar/{grupo}/{pessoa}', 'Admin\GrupoController@removePermission')->name('admin.grupo.removePermission')->middleware('user.has.authcookie');
//Exclui permissão de pessoa ao grupo
Route::put ('admin/grupo/add/{grupo}', 'Admin\GrupoController@addPermission')->name('admin.grupo.addPermission')->middleware('user.has.authcookie');

//Rotas de pessoa - métodos personalizados
//Rota ajax autocomplet na criação do novo gestor
Route::post('/admin/pessoa/liveSearch', 'Admin\PessoaController@liveSearch')->name('admin.pessoa.liveSearch');

//Rotas de Boleto - Métodos personalizados
//Rota para retirar a publicação do boleto - altera isBoleto para "nao" (ENUM)
Route::get('admin/boleto/removePublication/{boleto}','Admin\BoletoController@removePublication')->name('admin.boleto.removePublication')->middleware('user.has.authcookie');
//Rota para Ativar a publicação do boleto - altera isBoleto para "sim" (ENUM)
Route::get('admin/boleto/activePublication/{boleto}','Admin\BoletoController@activePublication')->name('admin.boleto.activePublication')->middleware('user.has.authcookie');
//Rota para Exibir histórico de boletos de acordo com grupoID
Route::get('admin/boleto/histBoletoGrupo/{grupoID}','Admin\BoletoController@showAll')->name('admin.boleto.historicoDoGrupo')->middleware('user.has.authcookie');
//Rota para Copiar configurações de boletos de acordo com boleto Id
Route::get('admin/boleto/copyConf/{boleto}','Admin\BoletoController@copyConfig')->name('admin.boleto.copyConf')->middleware('user.has.authcookie');
//Rota para expandir visualização com mais detalhes do evento de boletos, tal como pessoas inscritas
Route::get('admin/boleto/expandido/{boleto}','Admin\BoletoController@boletoExpandido')->name('admin.boleto.expandido')->middleware('user.has.authcookie');

//Rotas de Consumidor - Métodos personalizados - Não autenticado por middleware pois esse controle é usado para a partir de um email a pessoa tirar segunda via 
//Rota para retirar segunda via do boleto 
Route::get('admin/consumidor/segundaVia/{codBoletoGerado}','Admin\ConsumidorController@segundaVia')->name('admin.consumidor.segundaVia');
//Rota para capturar status de pgto do boleto 
Route::get('admin/consumidor/status/{codBoletoGerado}','Admin\ConsumidorController@statusPgto')->name('admin.consumidor.statusPgto');


Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function(){
       
    Route::resource('boleto', 'BoletoController')->middleware('user.has.authcookie');
    //Rota de Controller do tipo resource, uma rota para todos os métodos do controller
    Route::resource('consumidor', 'ConsumidorController');
    //Rota de Controller do tipo resource, uma rota para todos os métodos do controller
    Route::resource('grupo', 'GrupoController')->middleware('user.has.authcookie');
    //Rota de Controller do tipo resource, uma rota para todos os métodos do controller
    Route::resource('pessoa', 'PessoaController');

});

/**
 * Rotas para o consumidor do sistema
 * Pessoa que se inscreve para o evento de boleto e gera boleto para pagamento
 */

//Rotas personalisadas de inscrição
//Rota que leva ao formulario de inscrição com base no boleto ID
Route::get('/inscricao/{boleto}', 'inscricao\InscricaoController@forumularioIndex')->name('inscricao.form');
//Rota ajax autocomplet na criação do novo gestor
Route::post('/inscricao/liveSearch', 'inscricao\InscricaoController@liveSearchPessoaUSP')->name('inscricao.liveSearch');
//Rota ajax autocomplet na criação do novo gestor
Route::post('/inscricao/liveSearchCEP', 'inscricao\InscricaoController@liveSearchCEP')->name('inscricao.liveSearchCEP');
//Rota que gera o boleto registrado com o webservice da USP
Route::post('/inscricao/geraBoleto/{boleto}', 'inscricao\InscricaoController@gerarBoletoRegistrado')->name('inscricao.gerarBoletoRegistrado');
//Rota que lista todos os Eventos de Boleto e seus links de inscrição que estão com período válido
Route::get('/inscricao', 'inscricao\InscricaoController@listaBoletosAtivosPublico')->name('inscricao.listaInscricoesAtivas');

