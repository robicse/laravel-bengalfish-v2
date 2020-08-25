<?php

use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('reviews')->delete();
        
        \DB::table('reviews')->insert(array (
            0 => 
            array (
                'reviews_id' => 17,
                'products_id' => 4,
                'customers_id' => 14,
                'customers_name' => 'Rehan',
                'reviews_rating' => 5,
                'reviews_status' => 1,
                'reviews_read' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'reviews_id' => 18,
                'products_id' => 4,
                'customers_id' => 14,
                'customers_name' => 'Rehan',
                'reviews_rating' => 5,
                'reviews_status' => 1,
                'reviews_read' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}