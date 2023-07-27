<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReadValidadeRequest;
use App\Models\Book;
use App\Models\Read;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReadController extends Controller
{
    public function store(ReadValidadeRequest $request, $book_id)
    {
        try {
            $book = Book::where('id', $book_id)->where('id_user', auth()->user()->id)->first();
            if ($book) {
                $read_previous = Read::where('id_book', $book_id)->orderBy('timestamp', 'desc')->first();
                try {
                    if ($read_previous) {
                        $this->verifyPreviousRead($read_previous, $request);
                    } else {
                        $book->read_start_date = $request->timestamp;
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'status' => 'error'
                    ]);
                }

                // Calcular o número de páginas lidas na leitura
                $pages_read = 0;
                if ($request->stopped_page > $book->page_current) {
                    $pages_read = $request->stopped_page - $book->page_current;
                    if ($book->page_current == 0) {
                        $pages_read -= 1;
                    }
                }

                // Se a pessoa parou na página 1, definir o tempo por página como 0
                $time_read_per_page = ($pages_read > 0) ? $request->time_read / $pages_read : 0;

                $read = new Read();
                $read->id_book = $book_id;
                $readSaved = $this->setRead($read, $request, $book, $pages_read, $time_read_per_page);

                $book->time_read_total += $request->time_read;
                $book->page_current = $request->stopped_page;
                $book->pages_read = $book->page_current - 1;
                $book->time_read_per_page = $book->pages_read >= 1 ? $book->time_read_total / $book->pages_read : 0;
                $book->save();

                return response()->json([
                    'message' => 'Leitura registrada com sucesso!',
                    'status' => 'success',
                    'read' => $readSaved
                ]);
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao cadastrar leitura!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function updateLastRead(ReadValidadeRequest $request, $book_id)
    {
        try {
            $book = Book::where('id', $book_id)->where('id_user', auth()->user()->id)->first();
            if ($book) {
                $readCurrent = Read::where('id_book', $book_id)->orderBy('timestamp', 'desc')->first();

                if ($readCurrent) {
                    $read_previous = Read::where('id_book', $book_id)->orderBy('timestamp', 'desc')->skip(1)->first();

                    try {
                        if ($read_previous) {
                            $this->verifyPreviousRead($read_previous, $request);
                        } else {
                            $book->read_start_date = $request->timestamp;
                        }
                    } catch (\Exception $e) {
                        return response()->json([
                            'message' => $e->getMessage(),
                            'status' => 'error'
                        ]);
                    }

                    $pages_read = 0;
                    if ($read_previous) { //siginifica que nao é a primeira leitura
                        if ($request->stopped_page > $read_previous->stopped_page) {
                            $pages_read = $request->stopped_page - $read_previous->stopped_page;
                        }
                    } else {
                        if ($request->stopped_page > $book->page_current) {
                            $pages_read = $request->stopped_page - $book->page_current;
                            if ($book->page_current == 0) {
                                $pages_read -= 1;
                            }
                        }
                    }

                    $time_read_per_page = ($pages_read > 0) ? $request->time_read / $pages_read : 0;

                    $book->time_read_total -= $readCurrent->time_read;
                    $readSaved = $this->setRead($readCurrent, $request, $book, $pages_read, $time_read_per_page);
                    $book->time_read_total += $request->time_read;
                    $book->page_current = $request->stopped_page;
                    $book->pages_read = $book->page_current - 1;
                    $book->time_read_per_page = $book->pages_read >= 1 ? $book->time_read_total / $book->pages_read : 0;
                    $book->save();

                    return response()->json([
                        'message' => 'Leitura atualizada com sucesso!',
                        'status' => 'success',
                        'read' => $readSaved
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Leitura não encontrada!',
                        'status' => 'error'
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar leitura!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroyLastRead($book_id)
    {
        try {
            $book = Book::where('id', $book_id)->where('id_user', auth()->user()->id)->first();
            if ($book) {
                $read = Read::where('id_book', $book_id)->orderBy('timestamp', 'desc')->first();

                if ($read) {
                    // Verifica se é a primeira leitura
                    $is_first_read = Read::where('id_book', $book_id)->count() === 1;

                    // Resto do código existente...

                    // Delete a última leitura
                    $read->delete();

                    if ($is_first_read) {
                        // Ajuste para a primeira leitura (divisão por zero)
                        $book->page_current = 0;
                        $book->time_read_total = 0;
                        $book->time_read_per_page = 0;
                        $book->read_start_date = null;
                    } else {
                        // Cálculo normal do tempo médio por página após a exclusão da última leitura
                        $page_current = $book->page_current;
                        $book->page_current = $page_current - $read->pages_read;
                        $page_current -= $read->pages_read;
                        $book->time_read_total -= $read->time_read;

                        //TODO: RESOLVER
                        if ($page_current >= 2) {
                            $book->time_read_per_page = $book->time_read_total / $page_current - 1;
                        } else {
                            $book->time_read_per_page = $book->time_read_total;
                        }
                    }

                    // Salva o registro do livro atualizado
                    $book->save();

                    return response()->json([
                        'message' => 'Leitura excluída com sucesso!',
                        'status' => 'success',
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Não há leitura para ser excluída',
                        'status' => 'error'
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir leitura!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function finishRead($id_book)
    {

        try {
            $book = Book::where('id', $id_book)->where('id_user', auth()->user()->id)->first();
            if ($book) {

                if ($book->read_start_date == null) {
                    return response()->json([
                        'message' => 'Não é possível finalizar a leitura de um livro que não foi iniciado!',
                        'status' => 'error'
                    ], 404);
                }
                $book->page_current = 0;
                $book->accumulated_read_time += $book->time_read_total;
                $book->pages_read = 0;
                $book->time_read_total = 0;
                $book->time_read_per_page = 0;
                $book->read_end_date = null;
                $book->read_start_date = null;
                $book->last_read_complete = Carbon::now();
                $book->how_many_times_read += 1;
                $book->save();
                Read::where('id_book', $id_book)->delete();

                return response()->json([
                    'message' => 'Leitura completa registrada com sucesso!',
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'message' => 'Livro não encontrado!',
                    'status' => 'error'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao finalizar leitura!',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    private function setRead(Read $read, ReadValidadeRequest $request, Book $book, $pages_read, $time_read_per_page)
    {
        $read->timestamp = $request->timestamp;
        $read->time_read = $request->time_read;
        $read->stopped_page = $request->stopped_page;
        $read->pages_read = $pages_read;
        $read->time_read_per_page = $time_read_per_page;
        $read->comments = $request->comments;
        $read->section_where_stopped = $request->section_where_stopped;
        $read->save();
        try {
            $readSaved = Read::where('id_book', $book->id)->orderBy('timestamp', 'desc')->first();
            return $readSaved;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function verifyPreviousRead(Read $read_previous, ReadValidadeRequest $request)
    {
        if ($request->timestamp <= $read_previous->timestamp) {
            throw new \Exception('O momento em que leu deve ser superior à última leitura!');
        }
        if ($request->stopped_page < $read_previous->stopped_page) {
            throw new \Exception('Página em que parou deveria ser maior ou até igual a página da leitura anterior!');
        }
    }
}
