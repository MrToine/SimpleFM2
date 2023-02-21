<?php


use Phinx\Seed\AbstractSeed;

class NewsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [];
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++)
        {
            $date = $faker->unixTime('now');
        	$data[] = [
                'name' => $faker->word(),
                'slug' => $faker->word(),
                'content' => $faker->text(),
                'created_date' => date('Y-m-d H:i:s', $date),
                'updated_date' =>date('Y-m-d H:i:s', $date)
            ];
        }

        $this->table('news')
            ->insert($data)
            ->save();
    }
}
