<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Character\Character;
use App\Models\User\AdminRecord;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    //protected $connection = 'sprp';
    protected $table = 'accounts';

    protected $primaryKey = 'account_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_name',
        'account_password',
        'account_email',
        'account_discord',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'account_password',
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
            'account_password' => 'hashed',
            'account_quiz_cooldown' => 'datetime',
        ];
    }

    public function characters(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Character::class, 'account_id', 'account_id');
    }

    public function adminRecords(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(AdminRecord::class, 'account_id', 'account_id');
    }

    public function is_admin(): bool {
        return $this->account_stafflevel > 1;
    }

    // lazy load since we just need the count
    public function getCharacterCount(): int {
        return $this->characters->count();
    }

    public function setQuizStatus(string $status): void
    {
        $this->account_quiz_status = $status;
        $this->save();
    }

    public function getQuizStatus(bool $raw = false) {
        return $raw ? $this->account_quiz_status : ucfirst($this->account_quiz_status);
    }
    public function hasTakenQuiz(): bool
    {
        return $this->account_quiz_status === 'passed';
    }

    public function hasVerifiedEmail(): bool
    {
        return is_null($this->account_email_verified_at);
    }

    public function getAuthPassword() {
        return $this->account_password;
    }

    public function connections(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Connection::class, 'e_session_acc', 'account_id');
    }

    public function username(): string
    {
        return 'account_email';
    }

    public function getEmailForPasswordReset()
    {
        return $this->account_email;
    }

    // TODO: Disable this asap and change it to proper verification!
    public function canAccessPanel(Panel $panel): bool
    {
        $email = explode('@', $this->account_email);
        return $email[1] === 'singleplayer-roleplay.com';
    }

    public function getAuthPasswordName()
    {
        return 'account_password';
    }

    public function getFilamentName(): string
    {
        return $this->getAttributeValue('account_email');
    }
}
