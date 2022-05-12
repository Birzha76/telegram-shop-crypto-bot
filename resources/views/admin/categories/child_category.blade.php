<option value="{{ $child_category->id }}" @if(isset($current_category)) {{ $current_category->category_id == $child_category->id ? 'selected' : '' }} @endif>{{ $child_category->name }}</option>
@if ($child_category->categories)
    @foreach($child_category->categories as $childCategory)
        @if(isset($current_category))
            @include('admin.categories.child_category', ['child_category' => $childCategory, 'current_category' => $current_category])
        @else
            @include('admin.categories.child_category', ['child_category' => $childCategory])
        @endif
    @endforeach
@endif
