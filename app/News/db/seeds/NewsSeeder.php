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

        for ($i = 0; $i < 100; ++$i) {
            //$date = $faker->unixTime('now');
            $data[] = [
                'name' => 'News ' . $i,
                'slug' => 'news-' . $i,
                'content' => 'lorem ipsum blablabla',
                'created_date' => date('Y-m-d H:i:s', '155477852'),
                'updated_date' => date('Y-m-d H:i:s', '155477852')
            ];
        }

        $this->table('news')
            ->insert($data)
            ->save();
    }
}
