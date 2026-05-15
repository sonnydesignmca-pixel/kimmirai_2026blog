@props(['type'=>'submit'])
<button type="{{ $type }}" {{ $attributes(["class"=>"text-white bg-blue-600 rounded-xl text-semibold p-3 m-2 shadow-md border border-transparent hover:bg-blue-500 cursor-pointer focus:border-white"]) }} >{{ $slot }}</button>
