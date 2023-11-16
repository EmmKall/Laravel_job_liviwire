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
    #[Rule('nullable|image|max:1024')]
    public $image_path;
    public $imageKey;

    public function save() {

        $this->validate();
        $post = Post::create( $this->only( 'title', 'content', 'category_id' ) );
        //Post_Tag Save
        $post->tag()->attach( $this->tag );
        //Upload file if exists
        if( $this->image_path ){
            $post->image_path = $this->image_path->store( 'posts' );
            $post->save();
            $this->$imageKey = rand();
        }
        //Clean inputs
        $this->reset();

    }

}
