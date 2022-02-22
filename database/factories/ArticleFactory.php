<?php

namespace Database\Factories;

use App\Models\ArticleType;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $markdown = '## Table Of Contents

## Font

### Bold
**Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt dolor quo velit officiis aliquam cum maxime? Repellat, temporibus. Corrupti earum architecto velit vel.**

### Strikethrough
~~Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt dolor quo velit officiis aliquam cum maxime? Repellat, temporibus. Corrupti earum architecto velit vel.~~

### Italics
*Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt dolor quo velit officiis aliquam cum maxime? Repellat, temporibus. Corrupti earum architecto velit vel.*

### Underline
__Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt dolor quo velit officiis aliquam cum maxime? Repellat, temporibus. Corrupti earum architecto velit vel.__

## Lists

### Task List
- [ ] task
- [ ] task

### Ordered List
1. item
1. item

### Unordered List
- item
- item';

        return [
            'title' => $this->faker->name(),
            'markdown' => $markdown,
            'excerpt' => substr($markdown, 0, 100),
            'user_id' => 1,
            'published_at' => null,
            'article_type_id' => ArticleType::POST,
        ];
    }
}
