<?php
/**
 * Created by PhpStorm.
 * User: urmila
 * Date: 27/3/15
 * Time: 11:51 AM
 */


use Illuminate\Database\Seeder;
use App\Properties;
use App\User;

class PropertyTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('properties')->delete();
        $type = array('Buy','Lease');
        $location = array('Vashi','Koperkhene','Ghansoli','Navi Mumbai','Bandra','V.T. Station','Kurla','Virar','Virat','Central','Dahisar','Katargam','Adajan');
        $area = array('100 sq ft','200 sq ft','300 sq ft','400 sq ft','500 sq ft','600 sq ft','700 sq ft','800 sq ft','900 sq ft','1000 sq ft','1100 sq ft','1200 sq ft','1300 sq ft','1400 sq ft','1500 sq ft','1600 sq ft');
        $price = array('10000','20000','30000','40000','50000','60000','70000','80000','90000','100000','110000','120000','130000','140000','150000','160000','170000','180000','190000','200000','210000');
        $approved = array(1,0);
        $title = array('2-BHK','3-BHK','4-BHK','5-BHK','6-BHK','Home','Flat','Good Flat');
        $description = array('2-BHK','3-BHK','4-BHK','5-BHK','6-BHK','Home','Flat','Good Flat');

        for ($i = 0; $i < 49; $i++)
        {
            $userId = rand(1,50);
            $user = User::find($userId);
            $faker = Faker\Factory::create();

            $pro = new Properties();
            $pro->agentId = $userId;
            $pro->clientId = rand(1,50);
            $pro->title = $title[array_rand($title, 1)];
            $pro->description = $description[array_rand($description, 1)];
            $pro->clientEmail =  $faker->email;
            $pro->address = $area[array_rand($area, 1)];
            $pro->location = $location[array_rand($location, 1)];
            $pro->area = $area[array_rand($area, 1)];
            $pro->price = $price[array_rand($price, 1)];
            $pro->price = $price[array_rand($price, 1)];
            $pro->type = $type[array_rand($type, 1)];
            $pro->approved = $approved[array_rand($approved, 1)];
            $pro->save();

        }
    }
}