<?php
/**
 * Created by PhpStorm.
 * User: urmila
 * Date: 27/3/15
 * Time: 10:17 AM
 */
use Illuminate\Database\Seeder;
use App\Requirement;
use App\User;

class RequirementTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('requirements')->delete();
        $type = array('Buy','Rent');
        $location = array('Vashi','Koperkhene','Ghansoli','Navi Mumbai','Bandra','V.T. Station','Kurla','Virar','Virat','Central','Dahisar','Katargam','Adajan');
        $area = array('100 sq ft','200 sq ft','300 sq ft','400 sq ft','500 sq ft','600 sq ft','700 sq ft','800 sq ft','900 sq ft','1000 sq ft','1100 sq ft','1200 sq ft','1300 sq ft','1400 sq ft','1500 sq ft','1600 sq ft');
        $range = array('10','50','100','200','500');
        $price = array('10000','20000','30000','40000','50000','60000','70000','80000','90000','100000','110000','120000','130000','140000','150000','160000','170000','180000','190000','200000','210000');
        $priceRange = array('500','1000','1500','2000','2500','3000','3500','4000','4500','5000');
        $approved = array(1,0);
        $title = array('2-BHK','3-BHK','4-BHK','5-BHK','6-BHK','Home','Flat','Good Flat');
        $description = array('2-BHK','3-BHK','4-BHK','5-BHK','6-BHK','Home','Flat','Good Flat');

        for ($i = 0; $i < 50; $i++)
        {
            $userId = rand(1,50);
            $user = User::find($userId);

            $faker = Faker\Factory::create();
            $req = new Requirement();
            $req->agent_id = $userId;
            $req->client_id = rand(1,50);
            $req->client_email = $faker->email;
            $req->title = $title[array_rand($title, 1)];
            $req->description = $description[array_rand($description, 1)];
            $req->location = $location[array_rand($location, 1)];
            $req->area = $area[array_rand($area, 1)];
            $req->range = $range[array_rand($range, 1)];
            $req->price = $price[array_rand($price, 1)];
            $req->price_range = $priceRange[array_rand($priceRange, 1)];
            $req->type = $type[array_rand($type, 1)];
            $req->approved = $approved[array_rand($approved, 1)];
            $req->save(['user' => $user, 'requirement' => $req]);

        }
    }
}