<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;

class Formulario extends Component
{

    public $categories, $tags, $posts;

    #[ Rule('required', message: 'the title label is required') ]
    public $title;

    #[ Rule('required') ]
    public $content;

    #[ Rule('required') ]
    public $id;

    #[ Rule('required|exists:categories,id', as: 'category') ]
    public $category_id = '';

    #[ Rule('required|array', as: 'technologies') ]
    public $tagSelected = [];

    public $idEdit;
    #[Rule([
        'postEdit.title' => 'required',
        'postEdit.content' => 'required',
        'postEdit.category_id' => 'required|exists:categories,id',
        'postEdit.tag' => 'required|array',
    ],[
        'postEdit.cateogry_id'=>'category',
    ])]
    public $postEdit = [
        'category_id' => '',
        'title'       => '',
        'content'     => '',
        'tag'         => []
    ];



    public $postDestroyId = '';
    public $postDestroyTitle = '';

    public $openEditModal = false;
    public $openDestroyModal = false;

    public function mount() {
        $this->categories = Category::all();
        $this->tags       = Tag::all();
        $this->getPosts();
    }
    //Validations
    //When you create a rule, only can be call by function or directly with $this->validation
    /* public function rules() {
        return [
            'postEdit.title' => 'required',
            'postEdit.content' => 'required',
            'postEdit.category_id' => 'required|exists:categories,id',
            'postEdit.tag' => 'required|array',
        ];
    } */
    //Message per kind of error
    /* public function message(){
        return [
            'postEdit.title.require'       => 'The title label is required',
            'postEdit.content.require'     => 'The content label is required',
            'postEdit.category_id.require' => 'The category label is required',
        ];
    } */
    //Label of property
    /* public function validationAttrbutes() {
        return [
            'postEdit.title'          => 'title',
            'postEdit.content'        => 'content',
            'postEdit.category_id'    => 'category',
            'postEdit.tag'            => 'tag',
        ];
    } */

    public function getPosts() {
        $this->posts     = Post::all();
    }

    public function submit() {

        /* $this->validate([
            'category_id' => 'required',
            'title'       => 'required|min:5',
            'content'     => 'required|min:60',
            'tagSelected' => 'required'
        ],[                                         //Modified the complete message
            'title.required'       => 'The title label is required',
        ],[                                         //Modified the label
            'category_id' => 'category',
            'tagSelected' => 'technologies'
        ]); */

        /* $post = Post::create([
            'category_id' => $this->category_id,
            'title'       => $this->name,
            'content'     => $this->content
        ]); */

        $this->validate();

        $post = Post::create( $this->only( 'category_id', 'title', 'content' ) );
        //Post_Tag Save
        $post->tag()->attach( $this->tagSelected );
        //Clean inputs
        $this->reset([ 'category_id', 'title', 'content', 'tagSelected' ]);
        //Update posts
        $his->getPosts();
    }

    public function fillModalEdit( Post $post ) {
        $this->idEdit = $post->id;
        $this->postEdit = [
            'category_id' => $post->category_id,
            'content'     => $post->content,
            'title'       => $post->title,
            'tag'         => $post->tag->pluck( 'id' )->toArray()
        ];

        $this->openEditModal = true;
    }

    public function closeModal( $modal ) {
        if( $modal === 0 ) {
            $this->reset([ 'postEdit' ]);
            $this->openEditModal = false;
        } else {
            $this->reset([ 'postDestroyId', 'postDestroyTitle' ]);
            $this->openDestroyModal = false;
        }
    }

    public function submitUpdate(){
        $this->resetValidation();
        $post = Post::find( $this->idEdit );
        if( $post === null ){ return; }
        $post->update([
            'title' => $this->postEdit['title'],
            'content' => $this->postEdit['content'],
            'category_id' => $this->postEdit['category_id'],
        ]);
        $post->tag()->sync( $this->postEdit['tag'] );
        $this->getPosts();
        $this->closeModal();
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
