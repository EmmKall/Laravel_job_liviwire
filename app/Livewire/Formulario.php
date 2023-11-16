<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Lazy;
use App\Livewire\Forms\CreateJobForm;
use App\Livewire\Forms\EditJobForm;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;

#[Lazy]
class Formulario extends Component
{
    use WithFileUploads;

    public CreateJobForm $createJob;
    public EditJobForm   $editJob;

    public $categories, $tags, $posts;

    public $postDestroyId = '';
    public $postDestroyTitle = '';

    public $openDestroyModal = false;

    public function placeholder() {
        return <<<'HTML'
            <div>
                <p>Cargando...</p>
            </div>
        HTML;
    }

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
        $this->dispatch( 'notification', 'Job created' );
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
        $this->dispatch( 'notification', 'Job edited' );
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
        $this->dispatch( 'notification', 'Job deleted' );
    }

    public function render()
    {
        return view( 'livewire.formulario' );
    }

}
