<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Spotify;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpotifySearchController extends ApiController
{
    public function search(Request $request, string $type)
    {
        return $this->processRequest(function ($userData) use ($request, $type) {

            $queryParams = [
                'q' => $request->q,
                'type' => $type,
                'limit' => $request->limit,
                'market' => $request->market ?? 'ES',
            ];

            return Spotify::makeGetRequest('searchUrl', $queryParams, $userData['access_token']);
        }, $request);
    }
}
