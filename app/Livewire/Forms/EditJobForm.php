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

    public function fillModalEdit( $id ) {
        $post = Post::find( $id );
        $this->id = $post->id;
        $this->category_id = $post->category_id;
        $this->content     = $post->content;
        $this->title       = $post->title;
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
            'category_id' => $this->category_id,
        ]);
        $post->tag()->sync( $this->tag );
        $this->openEditModal = false;
    }

}
