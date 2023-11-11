<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Livewire\Forms\CreateJobForm;
use App\Livewire\Forms\EditJobForm;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;

class Formulario extends Component
{

    public CreateJobForm $createJob;
    public EditJobForm   $editJob;

    public $categories, $tags, $posts;

    public $postDestroyId = '';
    public $postDestroyTitle = '';

    public $openDestroyModal = false;

    public function mount() {
        $this->categories = Category::all();
        $this->tags       = Tag::all();
        $this->getPosts();
    }

    public function getPosts() {
        $this->posts = Post::all();
    }

    public function submit() {

        $this->createJob->save();
        //Update posts
        $this->getPosts();
    }

    public function closeModal( $modal ) {
        if( $modal === 0 ) {
            $this->editJob->openEditModal = false;
        } else {
            $this->reset([ 'postDestroyId', 'postDestroyTitle' ]);
            $this->openDestroyModal = false;
        }
    }

    public function editModal( $idJob ) {
        $this->resetValidation();
        $this->editJob->fillModalEdit( $idJob );
    }

    public function submitUpdate(){

        $this->editJob->update();

        $this->getPosts();
    }

    public function showDestroyModal( Post $post ) {
        $this->postDestroyTitle = $post->title;
        $this->postDestroyId    = $post->id;
        $this->openDestroyModal = true;
    }

    public function destroyPost() {
        Post::destroy( $this->postDestroyId );
        $this->reset([ 'postDestroyId', 'postDestroyTitle' ]);
        $this->closeModal( 1 );
        $this->getPosts();
    }

    public function render()
    {
        return view( 'livewire.formulario' );
    }
}
