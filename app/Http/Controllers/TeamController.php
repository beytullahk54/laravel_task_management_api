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
    /**
     * Tüm cevaplar standarize edilerek ApiResponse traitinden gelmektedir. Trait Controller içinde tanımlı olmaktadır.
     * Cevaplar istendiği kadar dile eklenebilmesi için lang dosyasında tanımlanmıştır.
     * Veriler cache mekanizması ile dönülmektedir. Şu an amaç redis olmaktadır. Ancak cache laravel tarafından desteklendiği için farklı motorlarda dahil edilebilir.
     * Membercreate ve memberdelete işlemleri team.owner yetkisi ile yapılır. Böylece takım sahibi harici kimse takım üyeleriyle ilgili işlem yapamaz.
     */
    public function index()
    {
        try {
            $data = Cache::remember('teams', 60, function () {
                return Team::cursorPaginate(10);
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
                __("validations.created_success"),
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function memberCreate($id,Request $request)
    {
        try {
            $team = Team::find($id);

            if (!$team) {
                return $this->error(__("not_found"), null, 404);
            }

            $team->members()->attach($request->user_id);
            return $this->success(
                [],
                __("validations.member_added_success"),
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    public function memberDelete($id, $user_id)
    {
        try {

            $team = Team::find($id);

            if (!$team) {
                return $this->error(__("not_found"), null, 404);
            }

            $team->members()->detach($user_id);
            return $this->success(
                [],
                __("validations.deleted_success")
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }
}
