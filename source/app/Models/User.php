<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;
    protected $guard_name = 'web';
    protected function getDefaultGuardName(): string { return 'web'; }
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'username',
        'email',
        'email_verified_at',
        'password',
        'login_attempts',
        'login_max_attempts',
        'login_hold_minutes',
        'login_locked_until',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'login_locked_until' => 'datetime',
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function detailUserInformation($user_id)
    {
        $user_details = User::join('users_pegawai', 'users.id', '=', 'users_pegawai.id')
            ->select(
                'users.*',
                'users_pegawai.*'
            )
            ->where('users.id', '=', $user_id)
            ->first();
        if (!$user_details) {
            $user_details = User::join('users_perusahaan', 'users.id', '=', 'users_perusahaan.id')
                ->select(
                    'users.username as nama_pegawai',
                    'users.email as jabatan',
                    'users_perusahaan.*'
                )
                ->where('users.id', '=', $user_id)
                ->first();
            $get_informasi_perusahaan = json_decode($user_details->json_perusahaan, true);
            $company_ids = array_column($get_informasi_perusahaan, 'id');
            // $data_perusahaan = Perusahaan::whereIn('id', $company_ids)->get();
            // $user_details->informasi_perusahaan = $data_perusahaan;
            $user_details->company_ids = $company_ids;
            Log::info('User details from perusahaan table: ' . $user_details);
        }
        return $user_details;
    }
    public static function userInformation($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $fecthdata = User::join('users_pegawai', 'users.id', '=', 'users_pegawai.id')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select(
                'users.*',
                'users_pegawai.*',
                'users_pegawai.id as id_user_pegawai',
                'roles.name as role_name'
            )
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('users.username', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users.email', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.id', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.nik', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.nama_pegawai', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        
        $jumlahdata = $fecthdata->count();
        
        return [
            'data' => $fecthdata,
            'total' => $jumlahdata
        ];
        
    }
    public static function detailUser($request){
        return User::join('users_pegawai', 'users.id', '=', 'users_pegawai.id')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'users_pegawai.*', 'roles.name as role_name', 'roles.id as id_role')
            ->where('users.id', '=', $request->id)
            ->first();
    }
    public function pegawai()
    {
        return $this->hasOne(
            Pegawai::class,
            'id', 
            'id'
        );
    }

    /**
     * Tentukan apakah akun sedang dalam keadaan terkunci karena melebihi batas percobaan login.
     */
    public function isLoginLocked(): bool
    {
        if (empty($this->login_locked_until)) {
            return false;
        }
        return $this->login_locked_until->isFuture();
    }

    /**
     * Hitung sisa waktu kunci dalam satuan menit (dibulatkan ke atas).
     */
    public function loginLockRemainingMinutes(): int
    {
        if (!$this->isLoginLocked()) {
            return 0;
        }
        return (int) now()->diffInMinutes($this->login_locked_until) + 1;
    }

    /**
     * Catat kegagalan login. Mengembalikan true jika akun baru saja terkunci.
     */
    public function recordLoginFailure(): bool
    {
        if ($this->isLoginLocked()) {
            return false;
        }

        $maxAttempts = (int) ($this->login_max_attempts > 0 ? $this->login_max_attempts : 3);
        $holdMinutes = (int) ($this->login_hold_minutes > 0 ? $this->login_hold_minutes : 10);

        $this->login_attempts = (int) $this->login_attempts + 1;

        if ($this->login_attempts >= $maxAttempts) {
            $this->login_locked_until = now()->addMinutes($holdMinutes);
            $this->login_attempts = 0;
            $this->save();
            return true;
        }

        $this->save();
        return false;
    }

    /**
     * Reset counter percobaan login ketika login berhasil.
     */
    public function resetLoginAttempts(): void
    {
        $this->login_attempts = 0;
        $this->login_locked_until = null;
        $this->save();
    }
}
