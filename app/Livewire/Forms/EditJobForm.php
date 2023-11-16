<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

use App\Models\Post;

class EditJobForm extends Form
{
    public $id;
    public $openEditModal = false;

    #[Rule('required')]
    public $title;
    #[Rule('required')]
    public $content;
    #[Rule('required|exists:categories,id')]
    public $category_id = '';
    #[Rule('required|array')]
    public $tag = [];
    #[Rule('nullable|image|max:1024')]
    public $image_path;
    public $imageKey;

    public function fillModalEdit( $id ) {
        $post = Post::find( $id );
        $this->id = $post->id;
        $this->category_id = $post->category_id;
        $this->content     = $post->content;
        $this->title       = $post->title;
        $this->image_path  = $post->image_path;
        $this->tag         = $post->tag->pluck( 'id' )->toArray();

        $this->openEditModal = true;
    }

    public function update() {
        $this->validate();

        $post = Post::find( $this->id );
        if( $post === null ){ return; }
        $post->update([
            'title'       => $this->title,
            'content'     => $this->content,
            'category_id' => $this->category_id
        ]);
        $post->tag()->sync( $this->tag );
        //Upload file if exists
        if( $this->image_path ){
            $post->image_path = $this->image_path->store( 'posts' );
            $post->save();
            $this->$imageKey = rand();
        }
        $this->openEditModal = false;
    }

}
