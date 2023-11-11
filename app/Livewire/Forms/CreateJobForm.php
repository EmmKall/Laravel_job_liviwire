<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

use App\Models\Post;

class CreateJobForm extends Form
{
    #[Rule('required|min:3')]
    public $title;
    #[Rule('required|min:10')]
    public $content;
    #[Rule('required|exists:categories,id')]
    public $category_id = '';
    #[Rule('required|array')]
    public $tag = [];

    public function save() {

        $this->validate();

        $post = Post::create( $this->only( 'title', 'content', 'category_id' ) );
        //Post_Tag Save
        $post->tag()->attach( $this->tag );
        //Clean inputs
        $this->reset();

    }

}
