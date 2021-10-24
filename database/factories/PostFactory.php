<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $markdown = '# Title

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec cursus mauris, vel placerat magna. Integer nibh nibh, tincidunt vitae pulvinar sed, volutpat quis lacus. Ut vitae justo eros. Nam eget ligula nibh. Aliquam erat volutpat. In at sagittis tortor. Sed venenatis ligula at diam pellentesque, nec dignissim nulla cursus. Ut ac sagittis nisi. Morbi cursus felis in sagittis commodo. Sed tempus vestibulum orci, sit amet efficitur enim aliquam ac. Integer quis lectus sit amet lacus commodo efficitur feugiat sed augue. Nullam vestibulum quam neque, in semper dui ornare a. Quisque sit amet nibh magna.

Curabitur sodales at lacus sed fermentum. Sed fermentum felis semper pharetra consectetur. Cras eget rhoncus turpis, sed commodo ex. Aenean rutrum urna ac maximus egestas. Cras scelerisque et elit nec viverra. Vivamus porttitor dictum nisl, in finibus est placerat nec. Sed non metus scelerisque, posuere nibh nec, lacinia ligula. Praesent a porta erat, sit amet sodales dui. Etiam a augue eu mi luctus tempus.

![alt text](https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg "Logo Title Text 1")

* List Item 1.
* List Item 2.
 * List Subitem 1

| Tables   |      Are      |  Cool |
|----------|:-------------:|------:|
| col 1 is |  left-aligned | $1600 |
| col 2 is |    centered   |   $12 |
| col 3 is | right-aligned |    $1 |

---
##title 2';

        return [
            'title' => $this->faker->name(),
            'markdown' => $markdown,
            'user_id' => 1,
        ];
    }
}
