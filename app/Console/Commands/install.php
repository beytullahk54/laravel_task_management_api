<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Task Manager Install';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('İşlemler başlatılıyor...');
        
        //Ön açıklama mesajı
        $this->info('Sistem içerisinde team için ve atama yapmak için 3 mail adresine ihtiyacımız var. Bu maillere email göndereceğiğimiz için lütfen kullandığınız email adresleri giriniz.');

        $email1 = $this->ask('Kullanıcı 1 email adresini girin:');
        $email2 = $this->ask('Kullanıcı 2 email adresini girin:');
        $email3 = $this->ask('Kullanıcı 3 email adresini girin:');

        $this->call('key:generate');
        $this->call('migrate');

        if(User::where('email',$email1)->count() == 0){
            User::factory()->create([
                'name' => 'Kahraman',
                'email' => $email1,
                'password' => Hash::make('12345678'),
            ]);
        }
        if(User::where('email',$email2)->count() == 0)
        {
            User::factory()->create([
                'name' => 'Deneme',
                'email' => $email2,
                'password' => Hash::make('12345678'),
            ]);
        }

        if(User::where('email',$email3)->count() == 0){
            User::factory()->create([
                'name' => 'Deneme',
                'email' => $email3,
                'password' => Hash::make('12345678'),
            ]);
        }
        
        $this->call('db:seed');
        $this->call('storage:link');
        $this->info('İşlemler tamamlandı.');
        
        return 0;
    }
    
    /**
     * .env dosyasını günceller
     */
    private function updateEnvFile($key, $value)
    {
        $envFile = base_path('.env');
        
        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
            
            file_put_contents($envFile, $envContent);
        }
    }
}
