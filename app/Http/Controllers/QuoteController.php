<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteUpvote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $lastId = $request->cookie('last_id', Quote::query()->first()->id);

        $votedFor = QuoteUpvote::query()
            ->where('ip', $request->ip())
            ->pluck('quote_id');

        $selectedQuote = Quote::query()
            ->select([
                DB::raw('quotes.id'),
                'quote',
                'author',
                DB::raw('count(quote_upvotes.id) as upvote_count')
            ])
            ->leftJoin('quote_upvotes', 'quote_upvotes.quote_id', '=', 'quotes.id')
            ->whereRaw(DB::raw("quotes.id != $lastId"))
            ->groupBy('quote')
            ->inRandomOrder()
            ->first();

        $quotes = Quote::query()
            ->select([
                DB::raw('quotes.id'),
                'quote',
                'author',
                DB::raw('count(quote_upvotes.id) as upvote_count')
            ])
            ->join('quote_upvotes', 'quote_upvotes.quote_id', '=', 'quotes.id')
            ->whereNot('quote_id', $selectedQuote->id)
            ->groupBy('quote_id')
            ->having('count', '>', 0)
            ->orderBy('upvote_count', 'desc')
            ->orderBy('quote_upvotes.updated_at', 'desc')
            ->get();

        return response()->view('quotes', [
            'votedFor' => $votedFor,
            'selectedQuote' => $selectedQuote,
            'quotes' => $quotes
        ])->withCookie(cookie('last_id', $selectedQuote->id));
    }

    public function upvote(Quote $quote, Request $request)
    {
        $ip = $request->ip();
        QuoteUpvote::query()
            ->firstOrCreate([
                'ip' => $ip,
                'quote_id' => $quote->id
            ]);

        return response()->json();
    }
}
