<?php

namespace App\Http\Controllers;

use App\Http\Requests\CharacterIndexRequest;
use App\Services\MarvelApiService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class CharacterController
 * @package App\Http\Controllers
 */
class CharacterController extends Controller
{
    /**
     * @var MarvelApiService
     */
    private $marvelApiService;

    /**
     * HomeController constructor.
     * @param MarvelApiService $marvelApiService
     */
    public function __construct(MarvelApiService $marvelApiService)
    {
        $this->marvelApiService = $marvelApiService;
    }

    /**
     * @param CharacterIndexRequest $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function index(CharacterIndexRequest $request)
    {
        try {
            $data = $request->validated();
            $name = $request->query('name') ?? null;
            $page = $request->query('page') ?? 1;
            [$characters, $total, $paginate, $limit] = $this->marvelApiService->characters($page, $name);
            return view('characters.index', compact('characters', 'total', 'paginate', 'name', 'page', 'limit'));
        } catch (Exception $ex) {
            report($ex);
            return redirect()->route('characters.error');
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        try {
            // verifica o valor recebido não é um valor numérico
            if (!is_numeric($id)) {
                // retorna página de erro
                return view('characters.error');
            }
            // busca  os dados do personagem
            $character = $this->marvelApiService->character($id);
            return view('characters.show', compact('character'));
        } catch (Exception $ex) {
            report($ex);
            return redirect()->route('characters.error');
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function error()
    {
        return view('characters.error');
    }
}
