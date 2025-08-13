<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Cache;
use App\Http\Request\Team\TeamStoreRequest;
use App\Http\Request\Team\TeamUpdateRequest;

class TeamController extends Controller
{
    public function index()
    {
        try {
            $data = Cache::remember('teams', 60, function () {
                return Team::paginate(10);
            });
    
            return $this->success(
                $data,
                'index'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
       
    }

    public function store(TeamStoreRequest $request)
    {
        try {
            $team = Team::create($request->validated());
            Cache::forget('teams');

            return $this->success(
                $team,
                'Takım başarıyla oluşturuldu.',
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function memberCreate($id)
    {
        try {

            return $this->success(
                [],
                'Takıma üye eklendi.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function memberDelete($id, $user_id)
    {
        try {

            return $this->success(
                [],
                'Takımdan üye silindi.'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }
}
