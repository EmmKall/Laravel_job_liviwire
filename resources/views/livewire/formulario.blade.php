<div class="bg-white shadow-md p-6 rounded-lg">
    <h1 class="text-center text-2xl font-bold">Job offers</h1>
    {{-- Formulario --}}
    <form class="mx-auto w-full md:w-2/4" wire:submit='submit'>
        <div class="mb-4">
            <x-label>Title: </x-label>
            <x-input class="w-full" wire:model.live='createJob.title' ></x-input>
            <x-input-error for="createJob.title" />
        </div>
        <div class="mb-4">
            <x-label>Content: </x-label>
            <x-text-area class="w-full" wire:model.live='createJob.content' ></x-text-area>
            <x-input-error for="createJob.content" />
        </div>
        <div class="mb-4">
            <x-label>Category: </x-label>
            <x-select class="w-full" wire:model.live='createJob.category_id' >
                <option value="" selected disabled>--Seleccione una opción--</option>
                @foreach ( $categories as $category )
                <option class="" value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-select>
            <x-input-error for="createJob.category_id" />
        </div>
        <div class="mb-4">
            <x-label>Image:</x-label>
            <input type="file" wire:model='createJob.image_path' wire:key='{{ $createJob->imageKey }}' />
        </div>
        @if ( $createJob->image_path )
            <div class="mb-4">
                <img src="{{ $createJob->image_path->temporaryUrl() }}" alt="post" class="w-[10rem] mx-auto" >
            </div>
        @endif
        <div class="mb-4">
            <x-label>Technologies: </x-label>
            <ul class="w-full flex flex-row justify-between flex-wrap">
                @foreach ( $tags as $tag )
                    <li class="w-1/3 md:w-1/6">
                        <x-label>
                            <x-checkbox value="{{ $tag->id }}" wire:model.live='createJob.tag'/>
                            {{ $tag->name }}
                        </x-label>
                    </li>
                @endforeach
            </ul>
            <x-input-error for="createJob.tag" />
        </div>
        <div class="mb-4 flex justify-end">
            <button type="submit" class="w-full md:w-1/3 px-8 py-2 text-white bg-blue-500 hover:bg-blue-700 rounded-md">Submit</button>
        </div>
    </form>
    {{-- Ofertas --}}
    <div class="mt-10">
        <h1 class="text-center text-2xl text-blue-700 font-bold">Ofertas</h1>
        <div class="w-full flex flex-row justify-between flex-wrap max-h-full overflow-auto">
            <table class="w-full overflow-x-scroll">
                <thead class="p-1 bg-blue-700 text-white text-center">
                    <th class="p-1 w-3/12 md:w-3/12">Image</th>
                    <th class="p-1 w-3/12 md:w-3/12">Title</th>
                    <th class="p-1 w-5/12 md:w-4/12">Description</th>
                    <th class="p-1 w-2/12 md:w-2/12">Tecnologies</th>
                    <th class="p-1 w-2/12 md:w-3/12">Actions</th>
                </thead>
                <tbody>
                    @foreach ( $posts as $key => $post )
                        <tr class="border-b-2 border-blue-700 {{ ( $key%2 === 0 ) ? 'bg-gray-50' : 'bg-gray-100' }}">
                            <td class="p-1">
                                @if( $post->image_path )
                                    <img src="{{ $post->image_path }}" alt="post" class="w-[1rem]">
                                    {{ $post->image_path }}
                                @endif
                            </td>
                            <td class="p-1">{{ $post->title }}</td>
                            <td class="p-1">{{ $post->content }}</td>
                            <td class="p-1">
                                <ul class="font-bold">
                                    @foreach ( $post->tag as $tag )
                                        <li class="font-normal" wire:key='tag-{{ $tag->id }}'>{{ $tag->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="p-1">
                                <div class="mx-auto flex flex-row justify-around text-xl">
                                    <button wire:click='editModal( {{ $post->id }} )' class="bg-green-500 hover:bg-green-700 rounded-full text-white"><i class="fa-regular fa-pen-to-square px-2 py-1"></i></button>
                                    <button wire:click='showDestroyModal( {{ $post->id }} )' class="bg-red-500 hover:bg-red-700 rounded-full text-white"><i class="fa-solid fa-trash-can px-2 py-1"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Modal $editJob.openEditModal --}}
    @if ( $editJob->openEditModal )
    <div class="inset-0 fixed bg-blue-800 bg-opacity-25 p-3 rounded-lg shadow-lg">
        <div class="py-12 text-black">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex flex-row justify-end">
                        <x-danger-button type="button" class="rounded text-white px-1 bg-red-500 hover:bg-red-700" wire:click='closeModal( 0 )'><i class="fa-solid fa-square-xmark"></i></x-danger-button>
                    </div>
                    <h1 class="text-center text-2xl font-bold text-blue-600">Updates job: {{ $post->title }}</h1>
                    <form class="mx-auto w-full md:w-3/4" wire:submit='submitUpdate'>
                        <div class="mb-4">
                            <x-label>Title: </x-label>
                            <x-input class="w-full" wire:model.live='editJob.title' ></x-input>
                            <x-input-error for="editJob.title" />
                        </div>
                        <div class="mb-4">
                            <x-label>Content: </x-label>
                            <x-text-area class="w-full" wire:model.live='editJob.content' ></x-text-area>
                        </div>
                        <div class="mb-4">
                            <x-label>Cateogry</x-label>
                            <x-select class="w-full" wire:model.live='editJob.category_id' >
                                <option value="" selected disabled>--Seleccione una opción--</option>
                                @foreach ( $categories as $category )
                                <option class="" value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="editJob.category_id" />
                        </div>
                        <div class="mb-4">
                            <x-label>Image:</x-label>
                            <input type="file" wire:model='editJob.image_path' wire:key='{{ $editJob->imageKey }}' />
                        </div>
                        <div class="mb-4">
                            <x-label>Technologies</x-label>
                            <ul class="w-full flex flex-row justify-between flex-wrap">
                                @foreach ( $tags as $tag )
                                    <li class="w-1/3 md:w-1/6">
                                        <x-label>
                                            <x-checkbox value="{{ $tag->id }}" wire:model.live='editJob.tag'/>
                                            {{ $tag->name }}
                                        </x-label>
                                    </li>
                                @endforeach
                            </ul>
                            <x-input-error for="editJob.tag" />
                        </div>
                        <div class="mb-4 flex justify-end">
                            <button type="submit" class="w-full md:w-1/3 px-8 py-2 text-white bg-blue-500 hover:bg-blue-700 rounded-md">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- Modal DestroyPost --}}
    @if ( $openDestroyModal )
        <div class="inset-0 fixed bg-blue-800 bg-opacity-25 p-3 rounded-lg shadow-lg">
            <div class="py-12 text-black">
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-2xl text-center font-bold text-blue-700">Are you sure to delete it?</h2>
                        <p class="text-center">{{ $postDestroyTitle }}</p>
                        <div class="w-full md:w-1/3 my-5 mx-auto flex flex-row justify-between">
                            <x-danger-button wire:click='closeModal( 1 )'><i class="fa-solid fa-xmark"></i></x-danger-button>
                            <x-button wire:click='destroyPost' class="bg-blue-500 hover:bg-blue-700"><i class="fa-solid fa-check"></i></x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push( 'js' )
        {{-- Agregar sweetlaert2 --}}
        <script>
            Livewire.on( 'notification', function( message ){ //message: Arrray
                alert( message );
                console.log( message );
            });
        </script>
    @endpush

</div>


