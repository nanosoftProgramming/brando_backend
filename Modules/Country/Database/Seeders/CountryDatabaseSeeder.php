<?php

namespace Modules\Country\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Country\App\Models\City;
use Modules\Country\App\Models\Country;

class CountryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Egypt', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kingdom of Saudi Arabia', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        Country::insert($countries);

        $cities = [
            ['name' => 'Alexandria', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ismailia', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aswan', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Asyut', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Luxor', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hurghada', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Damanhur', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beni Suef', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Port Said', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Giza', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mansoura', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Damietta', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sohag', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Suez', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zagazig', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arish', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tanta', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fayoum', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cairo', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Benha', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qena', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kafr El Sheikh', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mersa Matruh', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shebin El Kom', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Minya', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'New Valley', 'country_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // KSA Cities
            ['name' => 'Mecca', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jeddah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Taif', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Qunfudhah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Lith', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rabigh', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khulais', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Khurmah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ranyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turbah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Jumum', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Kamil', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Muwayh', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maysan', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adham', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Ardiyat', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bahrah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Medina Region Cities
            ['name' => 'Medina', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Yanbu', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Ula', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Mahd', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Hanakiyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Badr', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khaybar', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Ais', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wadi Al-Fara', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Qassim Region Cities
            ['name' => 'Buraydah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unaizah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ar Rass', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Midhnab', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Bukayriyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Badai', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Asyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'An Nabhaniyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ash Shimasiyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uyun Al Jiwa', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Riyadh Al Khabra', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uqlat As Suqur', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dhariyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Abanat', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Eastern Region Cities
            ['name' => 'Dammam', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Ahsa', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hafar Al-Batin', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jubail', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qatif', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khobar', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khafji', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ras Tanura', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buqayq', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nuairiyah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qaryat al-Ulya', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Adid', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Bayda', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Asir Region Cities
            ['name' => 'Abha', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khamis Mushait', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bisha', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Namas', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Muhayil Asir', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dhahran Al Janub', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tathlith', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sarat Abidah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rijal Almaa', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Balqarn', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ahad Rafidah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Majardah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Birk', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bariq', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tanomah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tareeb', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Harjah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Tabuk Region Cities
            ['name' => 'Tabuk', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Wajh', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Duba', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tayma', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Umluj', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Haql', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Bada', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Hail Region Cities
            ['name' => 'Hail', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baqaa', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Ghazalah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Shinan', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Hait', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Sulaimi', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Shamli', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mawqaq', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sumayra', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Northern Border Region Cities
            ['name' => 'Arar', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rafha', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turaif', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Uwayqilah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Jazan Region Cities
            ['name' => 'Jazan', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sabya', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Abu Arish', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samtah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baish', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ad Darb', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Harth', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Damad', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ar Raith', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Farasan', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ad Dair', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Aridah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ahad Al Masarihah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Edabi', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fifa', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'At Tuwal', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Harub', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Najran Region Cities
            ['name' => 'Najran', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sharurah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hubuna', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Badr Al Janoub', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Yadamah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thar', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khbash', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Al Baha Region Cities
            ['name' => 'Al Baha', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baljurashi', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Mandaq', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Mikhwah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qilwah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Aqiq', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Qara', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ghamid Al Zinad', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al Hajrah', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bani Hassan', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Al-Jawf Region Cities
            ['name' => 'Sakaka', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Al-Qurayyat', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dumat Al-Jandal', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tabarjal', 'country_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        City::insert($cities);
    }
}
