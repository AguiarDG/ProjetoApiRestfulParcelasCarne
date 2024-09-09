<?php

namespace App\Http\Controllers;

use App\Models\Carne;
use App\Http\Requests\StoreCarneRequest;
use App\Models\CarneParcela;
use DateInterval;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CarneController extends Controller
{
    /**
     * Lista todos os Carnês e suas respectivas parcelas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        try {
            $carnes = Carne::query()
                            ->select(
                                'carne.id_carne',
                                'carne.valor_total',
                                'carne.qtde_parcelas',
                                'carne.data_primeiro_vencimento',
                                'carne.periodicidade',
                                'carne_parcela.id_carne_parcela',
                                'carne_parcela.numero',
                                'carne_parcela.valor',
                                'carne_parcela.data_vencimento',
                                'carne_parcela.entrada',
                            )
                            ->join('carnes_parcelas as carne_parcela', 'carne_parcela.id_carne', '=', 'carne.id_carne')
                            ->orderBy('carne.id_carne')
                            ->get();

            return response()->json([
                'status' => true,
                'carnes' => $carnes,
            ], 200);
        } catch (QueryException $e) {
            // Erros de consulta
            return response()->json(['status' => false, 'msg' => 'Erro ao listar: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Outros tipos de erro
            return response()->json(['status' => false, 'msg' => 'Erro desconhecido: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCarneRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCarneRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            if (!in_array($request->periodicidade, ['mensal', 'semanal'])) {
                return response()->json(['status' => false, 'msg' => 'Campo periodicidade inválido']);
            }
            /**
             * Cria o Carne, passando somente os campos necessarios
             */
            $carne = Carne::create($request->only('valor_total', 'qtd_parcelas', 'data_primeiro_vencimento', 'periodicidade'));

            /**
             * Valida se tem valor de entrada e retorna o valor que deverá ser cobrando na parcela
             */
            $valor_parcelas_com_entrada = 0;
            $valor_parcelas_sem_entrada = 0;
            if ($request->valor_entrada > 0) {
                $data_parcela = new DateTime(date("Y-m-d"));

                $valor_parcelas_com_entrada = ($request->valor_total - $request->valor_entrada) / ($request->qtd_parcelas - 1);
            } else {
                $data_parcela = new DateTime($request->data_primeiro_vencimento);

                $valor_parcelas_sem_entrada = $request->valor_total / $request->qtd_parcelas;
            }

            for ($parcela=0; $parcela < $request->qtd_parcelas; $parcela++) {
                if ($valor_parcelas_com_entrada > 0) {
                    $valor_parcela = $parcela == 0 ? $request->valor_entrada : $valor_parcelas_com_entrada;

                    $carne_parcela[] = CarneParcela::create(['id_carne' => $carne->id_carne, 'numero' => $parcela + 1, 'valor' => number_format($valor_parcela, 2), 'data_vencimento' => $data_parcela->format('Y-m-d'), 'entrada' => $parcela == 0 ? 1 : 0]);
                } else {
                    $carne_parcela[] = CarneParcela::create(['id_carne' => $carne->id_carne, 'numero' => $parcela + 1, 'valor' => number_format($valor_parcelas_sem_entrada, 2), 'data_vencimento' => $data_parcela->format('Y-m-d')]);
                }

                switch ($request->periodicidade) {
                    case 'mensal':
                        $data_parcela->add(new DateInterval('P1M'));
                        break;

                    case 'semanal':
                        $data_parcela->add(new DateInterval('P1W'));
                        break;

                    default:
                        $data_parcela->add(new DateInterval('P1M'));
                        break;
                }
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            // Lidar com erros de consulta
            return response()->json(['status' => false, 'msg' => 'Erro ao cadastrar Carnê: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Lidar com outros tipos de erro
            return response()->json(['status' => false, 'msg' => 'Erro desconhecido: ' . $e->getMessage()]);
        }

        return response()->json([
            'status' => true,
            'id_carne' => $carne->id_carne,
            'total' => $carne->valor_total,
            'valor_entrada' => $request->valor_entrada,
            'parcelas' => $carne_parcela,
            'message' => 'Carnê e parcelas criadas com sucesso!',
        ], 201);
    }

    /**
     * Lista as parcelas de um determinado Carnê.
     *
     * @param \App\Models\Carne
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Carne $carne) : JsonResponse
    {
        try {
            $carne_parcelas = CarneParcela::query()
                                    ->select(
                                        'id_carne',
                                        'id_carne_parcela',
                                        'data_vencimento',
                                        'valor',
                                        'numero',
                                        DB::raw('IF(entrada = 1, "true", "false") as entrada'),
                                    )
                                    ->where('id_carne', '=', $carne->id_carne)
                                    ->orderBy('id_carne')
                                    ->get();

            return response()->json([
                'status' => true,
                'parcelas' => $carne_parcelas,
            ], 200);
        } catch (QueryException $e) {
            // Erros de consulta
            return response()->json(['status' => false, 'msg' => 'Erro ao listar: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Outros tipos de erro
            return response()->json(['status' => false, 'msg' => 'Erro desconhecido: ' . $e->getMessage()]);
        }
    }

}
