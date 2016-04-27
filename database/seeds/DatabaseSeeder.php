<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        \Illuminate\Support\Facades\DB::table('users')->delete();

        $users = array(
            ['name' => 'Dean Chenkie', 'email' => 'dean@gmail.com', 'password' => \Illuminate\Support\Facades\Hash::make('123123')],
            ['name' => 'Sean Parker', 'email' => 'sean@gmail.com', 'password' => \Illuminate\Support\Facades\Hash::make('123123')],
            ['name' => 'Ken Jake', 'email' => 'ken@gmail.com', 'password' => \Illuminate\Support\Facades\Hash::make('123123')],
            ['name' => 'Andrew Ho', 'email' => 'andrew@gmail.com', 'password' => \Illuminate\Support\Facades\Hash::make('123123')],
            ['name' => 'Steve Nguyen ', 'email' => 'steve@gmail.com', 'password' => \Illuminate\Support\Facades\Hash::make('123123')],
        );
        $products = [
            ['name' => 'Giò chả','price'=>10.5,'description'=>"Giò chả không phụ gia",'creator'=>1],
            ['name' => 'Bánh ','price'=>12.5,'description'=>"Bánh mỳ kẹp giò ",'creator'=>1],
        ];

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user)
        {
            \App\User::create($user);

        }
        foreach ($products as $product)
        {
             \App\Models\Product::create($product);

        }
        Model::reguard();
    }
}
